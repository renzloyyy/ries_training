"""
queries.py
Named SQL queries used by the analytics API. Keeping them here (instead of
inline in main.py) makes it easy to tweak a query without touching the
routing/endpoint code.
"""

QUERIES: dict[str, str] = {
    # 1. Proposal counts grouped by status
    "status_distribution": """
        SELECT
            status_code,
            COUNT(*) AS total_proposals
        FROM fact_research_proposal
        GROUP BY status_code
        ORDER BY total_proposals DESC;
    """,

    # 2. Proposal counts grouped by campus
    "proposals_by_campus": """
        SELECT
            c.campus_name,
            COUNT(*) AS total_proposals
        FROM fact_research_proposal f
        JOIN dim_campus c
            ON f.dim_campus_id = c.campus_id
        GROUP BY c.campus_name
        ORDER BY total_proposals DESC;
    """,

    # 3. Proposal counts grouped by program
    "proposals_by_program": """
        SELECT
            p.program_name,
            COUNT(*) AS total_proposals
        FROM fact_research_proposal f
        JOIN dim_program p
            ON f.dim_program_id = p.program_id
        GROUP BY p.program_name
        ORDER BY total_proposals DESC;
    """,

    # 4. Proposal counts grouped by submission year
    "proposals_by_year": """
        SELECT
            d.year,
            COUNT(*) AS total_proposals
        FROM fact_research_proposal f
        JOIN dim_date d
            ON f.dim_date_submission_id = d.date_id
        GROUP BY d.year
        ORDER BY d.year;
    """,

    # 5. Proposal counts grouped by year + quarter
    "proposals_by_quarter": """
        SELECT
            d.year,
            d.quarter,
            COUNT(*) AS total_proposals
        FROM fact_research_proposal f
        JOIN dim_date d
            ON f.dim_date_submission_id = d.date_id
        GROUP BY d.year, d.quarter
        ORDER BY d.year, d.quarter;
    """,

    # 6. Proposal counts grouped by year + month
    "proposals_by_month": """
        SELECT
            d.year,
            d.month_name,
            COUNT(*) AS total_proposals
        FROM fact_research_proposal f
        JOIN dim_date d
            ON f.dim_date_submission_id = d.date_id
        GROUP BY d.year, d.month, d.month_name
        ORDER BY d.year, d.month;
    """,

    # 7. Proposal counts grouped by research format
    "proposals_by_format": """
        SELECT
            rf.research_format_name,
            COUNT(*) AS total_proposals
        FROM fact_research_proposal f
        JOIN dim_research_format rf
            ON f.dim_research_format_id = rf.research_format_id
        GROUP BY rf.research_format_name
        ORDER BY total_proposals DESC;
    """,

    # 8. Proposal counts grouped by SDG
    "proposals_by_sdg": """
        SELECT
            s.sdg_code,
            s.sdg_name,
            COUNT(*) AS total_proposals
        FROM fact_research_proposal f
        JOIN dim_sdg s
            ON f.dim_sdg_id = s.sdg_id
        GROUP BY s.sdg_code, s.sdg_name
        ORDER BY total_proposals DESC;
    """,

    # 9. Proposal counts grouped by research agenda
    "proposals_by_agenda": """
        SELECT
            a.agenda_label,
            COUNT(*) AS total_proposals
        FROM fact_research_proposal f
        JOIN dim_agenda a
            ON f.dim_agenda_id = a.agenda_id
        GROUP BY a.agenda_label
        ORDER BY total_proposals DESC;
    """,

    # 10. Overall approval rate (single number, %)
    "approval_rate_overall": """
        SELECT
            ROUND(
                SUM(completed_count) * 100.0 /
                (SUM(completed_count) + SUM(pending_count)),
                2
            ) AS approval_rate_percentage
        FROM fact_research_proposal;
    """,

    # 11. Approval rate broken down by campus
    "approval_rate_by_campus": """
        SELECT
            c.campus_name,
            COUNT(*) AS total_proposals,
            SUM(f.completed_count) AS approved,
            SUM(f.pending_count) AS pending,
            ROUND(
                SUM(f.completed_count) * 100.0 /
                COUNT(*),
                2
            ) AS approval_rate
        FROM fact_research_proposal f
        JOIN dim_campus c
            ON f.dim_campus_id = c.campus_id
        GROUP BY c.campus_name
        ORDER BY approval_rate DESC;
    """,

    # 12. Proposal counts by campus + program
    "campus_program_breakdown": """
        SELECT
            c.campus_name,
            p.program_name,
            COUNT(*) AS total_proposals
        FROM fact_research_proposal f
        JOIN dim_program p
            ON f.dim_program_id = p.program_id
        JOIN dim_campus c
            ON p.campus_id = c.campus_id
        GROUP BY c.campus_name, p.program_name
        ORDER BY c.campus_name, total_proposals DESC;
    """,

    # 13. Proposal counts by campus + SDG
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
        GROUP BY c.campus_name, s.sdg_name
        ORDER BY c.campus_name, total_proposals DESC;
    """,
}