"""
queries_ries.py
Named SQL queries against the soulsuedu_ries database.

TWO SOURCES are in play:
  1. ri_submission  — the proposal / research-output pipeline (legacy panel).
  2. clean_publications — the curated, de-duplicated published-papers dataset
     (new Publications Dashboard panel). All queries below that target
     clean_publications use `is_primary_record = 1` to exclude duplicates
     identified during data-cleaning.

CLEAN_PUBLICATIONS SCHEMA NOTES
--------------------------------
  - is_primary_record  : 1 = canonical record, 0 = duplicate — always filter to 1
  - year_published     : INT  publication year
  - month_published    : INT  1–12
  - campus             : VARCHAR  campus name
  - indexing_tier      : VARCHAR  'Scopus' | 'International' | 'Local' | etc.
  - publication_name   : VARCHAR  journal / conference name
  - num_pages_int      : INT nullable  parsed page count
  - link_status        : VARCHAR  'Valid' | 'Broken' | NULL
  - flag_duplicate_title : INT 0/1
  - flag_bad_link        : INT 0/1
  - flag_bad_date        : INT 0/1
  - flag_null_date       : INT 0/1
  - flag_null_indexing   : INT 0/1
"""

# ---------------------------------------------------------------------------
# Legacy ri_submission queries (kept intact for the old Publications panel
# endpoints that still reference these keys).
# ---------------------------------------------------------------------------

_COMPLETED_COND = "rs.Completed_RouteStatus = 6 AND rs.completed_approved_at IS NOT NULL"

RIES_QUERIES: dict[str, str] = {

# ------------------------------------------------------------------
# Summary / KPI  (clean_publications)
# ------------------------------------------------------------------

# Single-row summary card — now follows the exact KPI query you provided,
# with counts and percentages for Scopus and International publications.
"pub_summary_kpis": """
SELECT
    COUNT(*) AS total_publications,
    COUNT(DISTINCT campus) AS total_campuses,
    SUM(indexing_tier = 'Scopus') AS scopus_publications,
    ROUND(
        100 * SUM(indexing_tier = 'Scopus') / COUNT(*),
        2
    ) AS scopus_percentage,
    SUM(indexing_tier = 'International') AS international_publications,
    ROUND(
        100 * SUM(indexing_tier = 'International') / COUNT(*),
        2
    ) AS international_percentage
FROM clean_publications
WHERE is_primary_record = 1;
""",

# ------------------------------------------------------------------
# Yearly trend  (clean_publications)
# ------------------------------------------------------------------

# One row per year — exact yearly publications trend.
"pub_by_year": """
SELECT
    year_published,
    COUNT(*) AS total_publications
FROM clean_publications
WHERE is_primary_record = 1
GROUP BY year_published
ORDER BY year_published;
""",

# ------------------------------------------------------------------
# Monthly trend  (clean_publications)
# ------------------------------------------------------------------

# One row per year+month — same query you supplied, with an optional
# year filter added so the current endpoint can still narrow results.
"pub_monthly_trend": """
SELECT
    year_published,
    month_published,
    COUNT(*) AS total
FROM clean_publications
WHERE is_primary_record = 1
  AND (%(year)s IS NULL OR year_published = %(year)s)
GROUP BY year_published, month_published
ORDER BY year_published, month_published;
""",

# ------------------------------------------------------------------
# By campus  (clean_publications)
# ------------------------------------------------------------------

# Campus totals — same query you supplied, with an optional year filter
# preserved for the current API contract.
"pub_by_campus": """
SELECT
    campus,
    COUNT(*) AS total_publications
FROM clean_publications
WHERE is_primary_record = 1
  AND (%(year)s IS NULL OR year_published = %(year)s)
GROUP BY campus
ORDER BY total_publications DESC;
""",

# ------------------------------------------------------------------
# Indexing tier  (clean_publications)
# ------------------------------------------------------------------

# Counts + percentage share by indexing tier. This follows your query,
# with the denominator filtered when a year is supplied.
"pub_by_indexing_tier": """
SELECT
    indexing_tier,
    COUNT(*) AS total,
    ROUND(
        COUNT(*) * 100.0 /
        (SELECT COUNT(*)
         FROM clean_publications
         WHERE is_primary_record = 1
           AND (%(year)s IS NULL OR year_published = %(year)s)),
        2
    ) AS percentage
FROM clean_publications
WHERE is_primary_record = 1
  AND (%(year)s IS NULL OR year_published = %(year)s)
GROUP BY indexing_tier
ORDER BY total DESC;
""",

# ------------------------------------------------------------------
# Campus × indexing tier grouped breakdown  (clean_publications)
# ------------------------------------------------------------------

# Campus × indexing-tier grouped breakdown — same query you supplied,
# with an optional year filter for the existing endpoint.
"pub_campus_indexing": """
SELECT
    campus,
    indexing_tier,
    COUNT(*) AS total
FROM clean_publications
WHERE is_primary_record = 1
  AND (%(year)s IS NULL OR year_published = %(year)s)
GROUP BY campus, indexing_tier
ORDER BY campus, total DESC;
""",

# ------------------------------------------------------------------
# Year × campus heatmap / stacked bar.
# ------------------------------------------------------------------

"pub_year_campus": """
SELECT
    year_published,
    campus,
    COUNT(*) AS total
FROM clean_publications
WHERE is_primary_record = 1
GROUP BY year_published, campus
ORDER BY year_published, campus;
""",

# ------------------------------------------------------------------
# Top 10 journals — same ranking query you supplied, with an optional
# year filter kept for the existing API call.
# ------------------------------------------------------------------

"pub_top_journals": """
SELECT
    publication_name,
    COUNT(*) AS total_publications
FROM clean_publications
WHERE is_primary_record = 1
  AND (%(year)s IS NULL OR year_published = %(year)s)
GROUP BY publication_name
ORDER BY total_publications DESC
LIMIT 10;
""",

# ------------------------------------------------------------------
# Average pages — retained for any widget still using this metric.
# ------------------------------------------------------------------

"pub_average_pages": """
SELECT
    ROUND(AVG(num_pages_int), 2) AS average_pages
FROM clean_publications
WHERE num_pages_int  IS NOT NULL
  AND is_primary_record = 1
  AND (%(year)s IS NULL OR year_published = %(year)s);
""",

# ------------------------------------------------------------------
# Data-quality flags  (clean_publications)
# ------------------------------------------------------------------

# Single-row flag summary — retained for audit-style widgets.
"pub_data_quality": """
SELECT
    SUM(flag_duplicate_title) AS duplicate_titles,
    SUM(flag_bad_link)        AS bad_links,
    SUM(flag_bad_date)        AS bad_dates,
    SUM(flag_null_date)       AS missing_dates,
    SUM(flag_null_indexing)   AS missing_indexing
FROM clean_publications;
""",

# ------------------------------------------------------------------
# Year-over-year growth rate  (clean_publications)
# ------------------------------------------------------------------

# One row per year showing current vs previous year counts and % growth.
# This matches the year-over-year growth query you provided.
"pub_growth_rate": """
SELECT
    current.year_published,
    current.total_publications  AS current_year_total,
    previous.total_publications AS previous_year_total,
    ROUND(
        (
            (current.total_publications - previous.total_publications)
            / previous.total_publications
        ) * 100,
        2
    ) AS growth_percentage
FROM
    (
        SELECT year_published, COUNT(*) AS total_publications
        FROM clean_publications
        WHERE is_primary_record = 1
        GROUP BY year_published
    ) current
JOIN
    (
        SELECT year_published, COUNT(*) AS total_publications
        FROM clean_publications
        WHERE is_primary_record = 1
        GROUP BY year_published
    ) previous
    ON current.year_published = previous.year_published + 1
ORDER BY current.year_published;
""",

# ------------------------------------------------------------------
# Quarterly trend  (clean_publications)
# ------------------------------------------------------------------

# Publication counts by quarter for 2020–2026, matching your query while
# still allowing the current endpoint to filter to one year.
"pub_quarterly_trend": """
SELECT
    year_published,
    CASE
        WHEN month_published BETWEEN 1 AND 3 THEN 'Q1'
        WHEN month_published BETWEEN 4 AND 6 THEN 'Q2'
        WHEN month_published BETWEEN 7 AND 9 THEN 'Q3'
        ELSE 'Q4'
    END AS quarter,
    COUNT(*) AS publications
FROM clean_publications
WHERE is_primary_record = 1
  AND year_published BETWEEN 2020 AND 2026
  AND (%(year)s IS NULL OR year_published = %(year)s)
GROUP BY year_published, quarter
ORDER BY year_published, quarter;
""",

# ------------------------------------------------------------------
# Campus contribution percentage  (clean_publications)
# ------------------------------------------------------------------

# Campus counts with share of total — same query you supplied, with the
# denominator aligned to the optional year filter used by the endpoint.
"pub_campus_contribution": """
SELECT
    campus,
    COUNT(*) AS publications,
    ROUND(
        COUNT(*) * 100.0 /
        (
            SELECT COUNT(*)
            FROM clean_publications
            WHERE is_primary_record = 1
              AND (%(year)s IS NULL OR year_published = %(year)s)
        ),
        2
    ) AS contribution_percentage
FROM clean_publications
WHERE is_primary_record = 1
  AND (%(year)s IS NULL OR year_published = %(year)s)
GROUP BY campus
ORDER BY publications DESC;
""",

# ------------------------------------------------------------------
# Year filter options  (clean_publications)
# ------------------------------------------------------------------

# Distinct published years, descending — populates the year-filter dropdown.
"pub_year_filter_options": """
SELECT DISTINCT year_published AS publication_year
FROM clean_publications
WHERE is_primary_record = 1
  AND year_published IS NOT NULL
ORDER BY year_published DESC;
""",

# ------------------------------------------------------------------
# Legacy ri_submission queries (unchanged — used by old endpoints)
# ------------------------------------------------------------------

"total_outputs": """
SELECT COUNT(*) AS total_outputs
FROM ri_submission rs
WHERE rs.deleted_at IS NULL
  AND (%(year)s IS NULL OR YEAR(rs.created_at) = %(year)s);
""",

"completed_outputs": f"""
SELECT COUNT(*) AS completed_outputs
FROM ri_submission rs
WHERE rs.deleted_at IS NULL
  AND {_COMPLETED_COND}
  AND (%(year)s IS NULL OR YEAR(rs.created_at) = %(year)s);
""",

"completion_rate": f"""
SELECT
    COUNT(*) AS total_outputs,
    SUM(CASE WHEN {_COMPLETED_COND} THEN 1 ELSE 0 END) AS completed_outputs,
    ROUND(
        SUM(CASE WHEN {_COMPLETED_COND} THEN 1 ELSE 0 END) * 100.0
        / NULLIF(COUNT(*), 0),
        2
    ) AS completion_rate_pct
FROM ri_submission rs
WHERE rs.deleted_at IS NULL
  AND (%(year)s IS NULL OR YEAR(rs.created_at) = %(year)s);
""",

"active_campuses": f"""
SELECT COUNT(DISTINCT rs.Campus) AS active_campuses
FROM ri_submission rs
JOIN campus_info ci ON ci.id = rs.Campus
WHERE rs.deleted_at IS NULL
  AND {_COMPLETED_COND}
  AND (%(year)s IS NULL OR YEAR(rs.created_at) = %(year)s);
""",

"outputs_by_campus": f"""
SELECT
    ci.campus AS campus_name,
    COUNT(rs.ID) AS total_outputs,
    SUM(CASE WHEN {_COMPLETED_COND} THEN 1 ELSE 0 END) AS completed_outputs,
    SUM(CASE WHEN NOT ({_COMPLETED_COND}) THEN 1 ELSE 0 END) AS pending_outputs
FROM ri_submission rs
JOIN campus_info ci ON ci.id = rs.Campus
WHERE rs.deleted_at IS NULL
  AND (%(year)s IS NULL OR YEAR(rs.created_at) = %(year)s)
GROUP BY rs.Campus, ci.campus
ORDER BY total_outputs DESC;
""",

"outputs_by_program": f"""
SELECT
    d.department_name AS program_name,
    ci.campus AS campus_name,
    COUNT(rs.ID) AS total_outputs,
    COUNT(rs.ID) AS completed_outputs
FROM ri_submission rs
JOIN department d ON d.id = rs.Department
JOIN campus_info ci ON ci.id = rs.Campus
WHERE rs.deleted_at IS NULL
  AND d.deleted_at IS NULL
  AND {_COMPLETED_COND}
  AND (%(year)s IS NULL OR YEAR(rs.created_at) = %(year)s)
GROUP BY rs.Department, d.department_name, rs.Campus, ci.campus
ORDER BY total_outputs DESC;
""",

"outputs_by_category": f"""
SELECT
    COALESCE(rs.Category, 'Unspecified') AS category,
    COUNT(rs.ID) AS total_outputs
FROM ri_submission rs
WHERE rs.deleted_at IS NULL
  AND {_COMPLETED_COND}
  AND (%(year)s IS NULL OR YEAR(rs.created_at) = %(year)s)
GROUP BY rs.Category
ORDER BY total_outputs DESC;
""",

"outputs_by_format": f"""
SELECT
    COALESCE(rs.ResearchFormat, 'Unspecified') AS research_format,
    COUNT(rs.ID) AS total_outputs
FROM ri_submission rs
WHERE rs.deleted_at IS NULL
  AND {_COMPLETED_COND}
  AND (%(year)s IS NULL OR YEAR(rs.created_at) = %(year)s)
GROUP BY rs.ResearchFormat
ORDER BY total_outputs DESC;
""",

"outputs_by_paper_status": f"""
SELECT
    CASE
        WHEN rs.paper_status = 1 THEN 'Published'
        WHEN rs.paper_status IS NOT NULL THEN 'Pending Publication'
        ELSE 'Not Set'
    END AS paper_status,
    COUNT(rs.ID) AS total_outputs
FROM ri_submission rs
WHERE rs.deleted_at IS NULL
  AND {_COMPLETED_COND}
  AND (%(year)s IS NULL OR YEAR(rs.created_at) = %(year)s)
GROUP BY paper_status
ORDER BY total_outputs DESC;
""",

"outputs_by_sdg": f"""
SELECT
    ss.sdg AS sdg_name,
    COUNT(DISTINCT ss.ppa_id) AS total_outputs
FROM sdg_submitted ss
JOIN ri_submission rs ON rs.ID = ss.ppa_id
WHERE rs.deleted_at IS NULL
  AND {_COMPLETED_COND}
  AND (%(year)s IS NULL OR YEAR(rs.created_at) = %(year)s)
GROUP BY ss.sdg
ORDER BY total_outputs DESC;
""",

"year_filter_options": """
SELECT DISTINCT YEAR(created_at) AS submission_year
FROM ri_submission
WHERE deleted_at IS NULL
ORDER BY submission_year DESC;
""",

"monthly_trend": f"""
SELECT
    YEAR(rs.completed_approved_at) AS yr,
    MONTH(rs.completed_approved_at) AS mo,
    COUNT(rs.ID) AS total_outputs
FROM ri_submission rs
WHERE rs.deleted_at IS NULL
  AND {_COMPLETED_COND}
  AND (%(year)s IS NULL OR YEAR(rs.completed_approved_at) = %(year)s)
GROUP BY YEAR(rs.completed_approved_at), MONTH(rs.completed_approved_at)
ORDER BY yr, mo;
""",

"yearly_trend": f"""
SELECT
    YEAR(rs.created_at) AS yr,
    COUNT(rs.ID) AS total_outputs,
    SUM(CASE WHEN {_COMPLETED_COND} THEN 1 ELSE 0 END) AS completed_outputs
FROM ri_submission rs
WHERE rs.deleted_at IS NULL
  AND YEAR(rs.created_at) IS NOT NULL
  AND (%(year)s IS NULL OR YEAR(rs.created_at) = %(year)s)
GROUP BY YEAR(rs.created_at)
ORDER BY yr;
""",

}
