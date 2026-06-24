"""
queries_ries.py
Named SQL queries against the soulsuedu_ries database (ri_submission etc.),
used to populate the Publications/Research Outputs panel.

IMPORTANT — completion logic:
ri_submission stores BOTH the proposal lifecycle and the completed-report
lifecycle in the same row. "Completed / Published" for this panel must be
judged on the COMPLETED-REPORT columns, not the proposal-approval columns
and not the generic Status flag:

    Completed_RouteStatus = 6           -> completed report reached final approval step
    completed_approved_at IS NOT NULL   -> completed-report approval timestamp stamped

A row only counts as a completed output when BOTH are true.

WHAT "PUBLICATIONS / RESEARCH OUTPUTS" SHOULD MEAN:
This panel represents finished, published research — not the proposal
pipeline. So every count-based breakdown here (category, format, SDG,
program, active campuses) is scoped to ONLY completed rows. A
still-pending/in-progress submission is not a "research output" yet —
it lives on the Proposals panel — so it is excluded outright rather than
counted alongside completed ones.

The ONE deliberate exception is `outputs_by_campus`, which powers the
"Completion rate by campus" signal-bar widget. A rate needs both a
numerator (completed) and a denominator (total submissions for that
campus) to mean anything, so that query keeps both. It never surfaces a
raw "pending count" anywhere in the UI — only a percentage.
"""

# Reused in every query so the "completed" definition can't drift between
# KPIs, charts, and the campus/program breakdown.
_COMPLETED_COND = "rs.Completed_RouteStatus = 6 AND rs.completed_approved_at IS NOT NULL"

RIES_QUERIES: dict[str, str] = {

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

# "Active campuses" for a publications panel means campuses that have
# actually produced a completed output — not just campuses with any
# submission sitting in the pipeline.
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

# Campus & program breakdown table now counts ONLY completed outputs per
# program — pending/in-progress submissions for that program are excluded
# from both the "total" and "completed" figures, so the two numbers
# describe finished work only (no pending submissions hiding inside the
# "total" column anymore).
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

# Category mix of completed/published outputs only.
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

# Research-format mix of completed/published outputs only.
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

# Only meaningful for outputs that have actually completed — an
# "in progress" proposal has no paper status yet.
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

# SDG alignment of completed/published outputs only — previously this
# counted every submitted SDG tag regardless of completion, which is why
# a single bar (e.g. "Quality Education") could show 300+ while the
# Completed-outputs KPI read 153. Now both numbers are drawn from the
# same completed-only population.
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

# Trended on completed_approved_at, so the curve tracks when outputs
# were actually finished/published, not when the original submission
# row was first created.
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

}