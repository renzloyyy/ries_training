"""
queries.py
Named SQL queries used by the analytics API. Keeping them here (instead of
inline in main.py) makes it easy to tweak a query without touching the
routing/endpoint code.
"""

QUERIES: dict[str, str] = {
# Every query accepts the same optional %(year)s parameter so the
# dashboard can request a single academic year without re-shaping
# the response format the Blade page already consumes.
"status_distribution": """
SELECT
f.status_code,
COUNT(*) AS total_proposals
FROM fact_research_proposal f
JOIN dim_date d
ON f.dim_date_submission_id = d.date_id
WHERE (%(year)s IS NULL OR d.year = %(year)s)
OR
(%(year)s IS NULL OR d.year >= YEAR(CURRENT_DATE())-4)
GROUP BY f.status_code
ORDER BY total_proposals DESC;
""",

"proposals_by_campus": """
SELECT
c.campus_name,
COUNT(*) AS total_proposals
FROM fact_research_proposal f
JOIN dim_campus c
ON f.dim_campus_id = c.campus_id
JOIN dim_date d
ON f.dim_date_submission_id = d.date_id
WHERE (%(year)s IS NULL OR d.year = %(year)s)
GROUP BY c.campus_name
ORDER BY total_proposals DESC;
""",

"proposals_by_program": """
SELECT
p.program_name,
COUNT(*) AS total_proposals
FROM fact_research_proposal f
JOIN dim_program p
ON f.dim_program_id = p.program_id
JOIN dim_date d
ON f.dim_date_submission_id = d.date_id
WHERE (%(year)s IS NULL OR d.year = %(year)s)
GROUP BY p.program_name
ORDER BY total_proposals DESC;
""",

"proposals_by_year": """
SELECT
d.year,
COUNT(*) AS total_proposals
FROM fact_research_proposal f
JOIN dim_date d
ON f.dim_date_submission_id = d.date_id
WHERE (%(year)s IS NULL OR d.year = %(year)s)
GROUP BY d.year
ORDER BY d.year;
""",

"completed_outputs_by_year": """
SELECT
-- Use the approval year when it exists, but fall back to the submission
-- year for completed records whose approval-date dimension is missing.
-- This keeps the overview trend aligned with the warehouse trace query and
-- avoids undercounting earlier completed proposals.
COALESCE(da.year, ds.year) AS year,
COUNT(*) AS completed_outputs
FROM fact_research_proposal f
LEFT JOIN dim_date da
ON f.dim_date_approval_id = da.date_id
LEFT JOIN dim_date ds
ON f.dim_date_submission_id = ds.date_id
WHERE f.status_code = 'C'
AND (
    %(year)s IS NULL
    OR COALESCE(da.year, ds.year) = %(year)s
)
GROUP BY COALESCE(da.year, ds.year)
ORDER BY year;
""",

"proposals_by_quarter": """
SELECT
d.year,
d.quarter,
COUNT(*) AS total_proposals
FROM fact_research_proposal f
JOIN dim_date d
ON f.dim_date_submission_id = d.date_id
WHERE (%(year)s IS NULL OR d.year = %(year)s)
GROUP BY d.year, d.quarter
ORDER BY d.year, d.quarter;
""",

"proposals_by_month": """
SELECT
d.year,
d.month_name,
COUNT(*) AS total_proposals
FROM fact_research_proposal f
JOIN dim_date d
ON f.dim_date_submission_id = d.date_id
WHERE (%(year)s IS NULL OR d.year = %(year)s)
GROUP BY d.year, d.month, d.month_name
ORDER BY d.year, d.month;
""",

"proposals_by_format": """
SELECT
-- Group blank or missing format names into a readable "Other" bucket so the
-- research-type chart still shows uncategorized records.
COALESCE(NULLIF(TRIM(rf.research_format_name), ''), 'Other Research Type') AS research_format_name,
COUNT(*) AS total_proposals
FROM fact_research_proposal f
JOIN dim_research_format rf
ON f.dim_research_format_id = rf.research_format_id
JOIN dim_date d
ON f.dim_date_submission_id = d.date_id
WHERE (%(year)s IS NULL OR d.year = %(year)s)
GROUP BY COALESCE(NULLIF(TRIM(rf.research_format_name), ''), 'Other Research Type')
ORDER BY total_proposals DESC;
""",

"proposals_by_sdg": """
SELECT
s.sdg_code,
s.sdg_name,
COUNT(f.dim_sdg_id) AS total_proposals
FROM dim_sdg s
LEFT JOIN fact_research_proposal f
ON f.dim_sdg_id = s.sdg_id
JOIN dim_date d
ON f.dim_date_submission_id = d.date_id
WHERE (%(year)s IS NULL OR d.year = %(year)s)
GROUP BY
s.sdg_code,
s.sdg_name
ORDER BY
total_proposals DESC;
""",



"proposals_by_agenda": """
SELECT
a.agenda_label,
COUNT(*) AS total_proposals
FROM fact_research_proposal f
JOIN dim_agenda a
ON f.dim_agenda_id = a.agenda_id
JOIN dim_date d
ON f.dim_date_submission_id = d.date_id
WHERE (%(year)s IS NULL OR d.year = %(year)s)
GROUP BY a.agenda_label
ORDER BY total_proposals DESC;
""",

"approval_rate_overall": """
SELECT
ROUND(
SUM(f.completed_count) * 100.0 /
NULLIF(SUM(f.completed_count) + SUM(f.pending_count), 0),
2
) AS approval_rate_percentage
FROM fact_research_proposal f
JOIN dim_date d
ON f.dim_date_submission_id = d.date_id
WHERE (%(year)s IS NULL OR d.year = %(year)s);
""",

"approval_rate_by_campus": """
SELECT
c.campus_name,
COUNT(*) AS total_proposals,
SUM(f.completed_count) AS approved,
SUM(f.pending_count) AS pending,
ROUND(
SUM(f.completed_count) * 100.0 /
NULLIF(COUNT(*), 0),
2
) AS approval_rate
FROM fact_research_proposal f
JOIN dim_campus c
ON f.dim_campus_id = c.campus_id
JOIN dim_date d
ON f.dim_date_submission_id = d.date_id
WHERE (%(year)s IS NULL OR d.year = %(year)s)
GROUP BY c.campus_name
ORDER BY approval_rate DESC;
""",

"campus_program_breakdown": """
SELECT
c.campus_name,
p.program_name,
COUNT(*) AS total_proposals,
SUM(f.completed_count) AS completed_proposals,
ROUND(
SUM(f.completed_count) * 100.0 /
NULLIF(COUNT(*), 0),
2
) AS completion_rate
FROM fact_research_proposal f
JOIN dim_program p
ON f.dim_program_id = p.program_id
JOIN dim_campus c
ON p.campus_id = c.campus_id
JOIN dim_date d
ON f.dim_date_submission_id = d.date_id
WHERE (%(year)s IS NULL OR d.year = %(year)s)
GROUP BY c.campus_name, p.program_name
ORDER BY c.campus_name, total_proposals DESC;
""",

"campus_sdg_breakdown": """
SELECT
c.campus_name,
s.sdg_name,
COUNT(*) AS total_proposals
FROM fact_research_proposal f
JOIN dim_campus c
ON f.dim_campus_id = c.campus_id
JOIN dim_sdg s
ON f.dim_sdg_id = s.sdg_id
JOIN dim_date d
ON f.dim_date_submission_id = d.date_id
WHERE (%(year)s IS NULL OR d.year = %(year)s)
GROUP BY c.campus_name, s.sdg_name
ORDER BY c.campus_name, total_proposals DESC;
""",
}
