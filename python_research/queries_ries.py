"""
queries_ries.py
Named SQL queries against the soulsuedu_ries database (ri_submission etc.),
used to populate the Publications/Research Outputs panel.
"""

RIES_QUERIES: dict[str, str] = {

"total_outputs": """
SELECT COUNT(*) AS total_outputs
FROM ri_submission
WHERE deleted_at IS NULL
  AND (%(year)s IS NULL OR YEAR(created_at) = %(year)s);
""",

"completed_outputs": """
SELECT COUNT(*) AS completed_outputs
FROM ri_submission
WHERE deleted_at IS NULL
  AND Status = 'C'
  AND (%(year)s IS NULL OR YEAR(created_at) = %(year)s);
""",

"completion_rate": """
SELECT
    COUNT(*) AS total_outputs,
    SUM(CASE WHEN Status = 'C' THEN 1 ELSE 0 END) AS completed_outputs,
    ROUND(
        SUM(CASE WHEN Status = 'C' THEN 1 ELSE 0 END) * 100.0
        / NULLIF(COUNT(*), 0),
        2
    ) AS completion_rate_pct
FROM ri_submission
WHERE deleted_at IS NULL
  AND (%(year)s IS NULL OR YEAR(created_at) = %(year)s);
""",

"active_campuses": """
SELECT COUNT(DISTINCT rs.Campus) AS active_campuses
FROM ri_submission rs
JOIN campus_info ci ON ci.id = rs.Campus
WHERE rs.deleted_at IS NULL
  AND (%(year)s IS NULL OR YEAR(rs.created_at) = %(year)s);
""",

"outputs_by_campus": """
SELECT
    ci.campus AS campus_name,
    COUNT(rs.ID) AS total_outputs,
    SUM(CASE WHEN rs.Status = 'C' THEN 1 ELSE 0 END) AS completed_outputs,
    SUM(CASE WHEN rs.Status = 'P' THEN 1 ELSE 0 END) AS pending_outputs
FROM ri_submission rs
JOIN campus_info ci ON ci.id = rs.Campus
WHERE rs.deleted_at IS NULL
  AND (%(year)s IS NULL OR YEAR(rs.created_at) = %(year)s)
GROUP BY rs.Campus, ci.campus
ORDER BY total_outputs DESC;
""",

"outputs_by_program": """
SELECT
    d.department_name AS program_name,
    ci.campus AS campus_name,
    COUNT(rs.ID) AS total_outputs,
    SUM(CASE WHEN rs.Status = 'C' THEN 1 ELSE 0 END) AS completed_outputs
FROM ri_submission rs
JOIN department d ON d.id = rs.Department
JOIN campus_info ci ON ci.id = rs.Campus
WHERE rs.deleted_at IS NULL
  AND d.deleted_at IS NULL
  AND (%(year)s IS NULL OR YEAR(rs.created_at) = %(year)s)
GROUP BY rs.Department, d.department_name, rs.Campus, ci.campus
ORDER BY total_outputs DESC;
""",

"outputs_by_category": """
SELECT
    COALESCE(Category, 'Unspecified') AS category,
    COUNT(ID) AS total_outputs,
    SUM(CASE WHEN Status = 'C' THEN 1 ELSE 0 END) AS completed_outputs
FROM ri_submission
WHERE deleted_at IS NULL
  AND (%(year)s IS NULL OR YEAR(created_at) = %(year)s)
GROUP BY Category
ORDER BY total_outputs DESC;
""",

"outputs_by_format": """
SELECT
    COALESCE(ResearchFormat, 'Unspecified') AS research_format,
    COUNT(ID) AS total_outputs,
    SUM(CASE WHEN Status = 'C' THEN 1 ELSE 0 END) AS completed_outputs
FROM ri_submission
WHERE deleted_at IS NULL
  AND (%(year)s IS NULL OR YEAR(created_at) = %(year)s)
GROUP BY ResearchFormat
ORDER BY total_outputs DESC;
""",

"outputs_by_paper_status": """
SELECT
    COALESCE(paper_status, 'Not Set') AS paper_status,
    COUNT(ID) AS total_outputs
FROM ri_submission
WHERE deleted_at IS NULL
  AND (%(year)s IS NULL OR YEAR(created_at) = %(year)s)
GROUP BY paper_status
ORDER BY total_outputs DESC;
""",

"outputs_by_sdg": """
SELECT
    ss.sdg AS sdg_name,
    COUNT(DISTINCT ss.ppa_id) AS total_outputs,
    SUM(CASE WHEN rs.Status = 'C' THEN 1 ELSE 0 END) AS completed_outputs
FROM sdg_submitted ss
JOIN ri_submission rs ON rs.ID = ss.ppa_id
WHERE rs.deleted_at IS NULL
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

"monthly_trend": """
SELECT
    YEAR(created_at) AS yr,
    MONTH(created_at) AS mo,
    COUNT(ID) AS total_outputs,
    SUM(CASE WHEN Status = 'C' THEN 1 ELSE 0 END) AS completed_outputs
FROM ri_submission
WHERE deleted_at IS NULL
  AND (%(year)s IS NULL OR YEAR(created_at) = %(year)s)
GROUP BY YEAR(created_at), MONTH(created_at)
ORDER BY yr, mo;
""",

}