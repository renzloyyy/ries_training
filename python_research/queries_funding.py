"""
queries_funding.py
Named SQL queries backing the Fundings dashboard panel.

Database: soulsuedu_ries (same connection as queries_ries.py — use
run_ries_query from db.py to execute these).

FUNDING SOURCE LOGIC (see original funding_dashboard_queries.sql):
    ri_submission is the master research table.
    "Funded / Approved with funding" means:
        - Route_Status = 6  AND  approved_at IS NOT NULL  (proposal approved)
        - ProposedBudget > 0  OR  ActualBudget > 0  OR
          institutionalAmount > 0  OR  OtherAgenciesAmount > 0

    Budget precedence used throughout:
        ActualBudget (confirmed spend) > ProposedBudget (not yet disbursed)
        > institutionalAmount + OtherAgenciesAmount (additive fallback
          when neither of the main budget fields is populated).

    SourceofFund  -> agencies.id      (funding body)
    Campus        -> campus_info.id
    Department    -> department.id   (program)

Every query accepts an optional named parameter :year. When :year is
NULL (i.e. no year filter selected on the dashboard), the clause is a
no-op and all approved/funded records are included. Pass {"year": None}
or {"year": 2024} when calling run_ries_query.
"""

# Reusable fragments so every panel applies the exact same funded-record
# definition and the exact same allocated-amount precedence.
_FUNDED_FILTER = """
    ri.deleted_at IS NULL
    AND ri.Route_Status = 6
    AND ri.approved_at IS NOT NULL
    AND (
        COALESCE(ri.ActualBudget, 0)           > 0
        OR COALESCE(ri.ProposedBudget, 0)      > 0
        OR COALESCE(ri.institutionalAmount, 0) > 0
        OR COALESCE(ri.OtherAgenciesAmount, 0) > 0
    )
"""

_YEAR_FILTER = "AND (%(year)s IS NULL OR YEAR(ri.approved_at) = %(year)s)"

_ALLOCATED_FUND_EXPR = """
    SUM(
        CASE
            WHEN ri.ActualBudget IS NOT NULL AND ri.ActualBudget > 0
                THEN ri.ActualBudget
            WHEN ri.ProposedBudget IS NOT NULL AND ri.ProposedBudget > 0
                THEN ri.ProposedBudget
            ELSE
                COALESCE(ri.institutionalAmount, 0)
                + COALESCE(ri.OtherAgenciesAmount, 0)
        END
    )
"""


FUNDING_QUERIES = {

    # ----------------------------------------------------------------
    # KPI cards
    # ----------------------------------------------------------------

    "total_funded_projects": f"""
        SELECT
            COUNT(*) AS total_funded_projects
        FROM ri_submission ri
        WHERE
            {_FUNDED_FILTER}
            {_YEAR_FILTER};
    """,

    "total_allocated_fund": f"""
        SELECT
            {_ALLOCATED_FUND_EXPR} AS total_allocated_fund
        FROM ri_submission ri
        WHERE
            {_FUNDED_FILTER}
            {_YEAR_FILTER};
    """,

    # ----------------------------------------------------------------
    # Funding by campus
    # ----------------------------------------------------------------

    "funding_by_campus": f"""
        SELECT
            ci.campus                                          AS campus_name,
            COUNT(ri.ID)                                       AS funded_projects,
            {_ALLOCATED_FUND_EXPR}                              AS total_allocated_fund
        FROM ri_submission ri
        JOIN campus_info ci
            ON ri.Campus = ci.id
        WHERE
            {_FUNDED_FILTER}
            {_YEAR_FILTER}
        GROUP BY ci.id, ci.campus
        ORDER BY total_allocated_fund DESC;
    """,

    # ----------------------------------------------------------------
    # Funding by category / format
    # ----------------------------------------------------------------

    # Category ALONE — feeds the "Funding by category" donut chart.
    # IMPORTANT: this must group by Category only. Grouping by
    # (Category, ResearchFormat) — as the old "funding_by_program" query
    # did — produces multiple rows sharing the same category label
    # (e.g. several "Research" rows split by format), which a donut
    # chart renders as duplicate same-named slices instead of one
    # combined slice.
    "funding_by_category": f"""
        SELECT
            ri.Category                                        AS program_category,
            COUNT(ri.ID)                                       AS funded_projects,
            {_ALLOCATED_FUND_EXPR}                              AS total_allocated_fund
        FROM ri_submission ri
        WHERE
            {_FUNDED_FILTER}
            {_YEAR_FILTER}
        GROUP BY ri.Category
        ORDER BY total_allocated_fund DESC;
    """,

    # Category + format breakdown — feeds the "Funding by research
    # format" bar chart only (renamed from "funding_by_program" to make
    # its actual grouping explicit and stop it being reused for the
    # category donut).
    "funding_by_format": f"""
        SELECT
            ri.Category                                        AS program_category,
            ri.ResearchFormat                                  AS research_format,
            COUNT(ri.ID)                                       AS funded_projects,
            {_ALLOCATED_FUND_EXPR}                              AS total_allocated_fund
        FROM ri_submission ri
        WHERE
            {_FUNDED_FILTER}
            {_YEAR_FILTER}
        GROUP BY ri.Category, ri.ResearchFormat
        ORDER BY total_allocated_fund DESC;
    """,

    # Granular drill-down: department within campus, used for the
    # campus/program detail table.
    "funding_by_department": f"""
        SELECT
            d.department_name                                  AS department,
            ci.campus                                          AS campus_name,
            COUNT(ri.ID)                                       AS funded_projects,
            {_ALLOCATED_FUND_EXPR}                              AS total_allocated_fund
        FROM ri_submission ri
        JOIN department d
            ON ri.Department = d.id
        JOIN campus_info ci
            ON d.campus = ci.id
        WHERE
            {_FUNDED_FILTER}
            AND d.deleted_at IS NULL
            {_YEAR_FILTER}
        GROUP BY d.id, d.department_name, ci.campus
        ORDER BY total_allocated_fund DESC;
    """,

    # ----------------------------------------------------------------
    # Funding over time
    # ----------------------------------------------------------------

    "funding_by_year": f"""
        SELECT
            YEAR(ri.approved_at)                               AS fiscal_year,
            COUNT(ri.ID)                                       AS funded_projects,
            {_ALLOCATED_FUND_EXPR}                              AS total_allocated_fund
        FROM ri_submission ri
        WHERE
            {_FUNDED_FILTER}
            {_YEAR_FILTER}
        GROUP BY YEAR(ri.approved_at)
        ORDER BY fiscal_year ASC;
    """,

    "funding_by_quarter": f"""
        SELECT
            fiscal_year,
            quarter,
            CONCAT(fiscal_year, ' Q', quarter)                AS period_label,
            funded_projects,
            total_allocated_fund
        FROM (
            SELECT
                YEAR(ri.approved_at)                           AS fiscal_year,
                QUARTER(ri.approved_at)                        AS quarter,
                COUNT(ri.ID)                                   AS funded_projects,
                {_ALLOCATED_FUND_EXPR}                          AS total_allocated_fund
            FROM ri_submission ri
            WHERE
                {_FUNDED_FILTER}
                {_YEAR_FILTER}
            GROUP BY YEAR(ri.approved_at), QUARTER(ri.approved_at)
        ) q
        ORDER BY fiscal_year ASC, quarter ASC;
    """,

    # Month-level granularity, mirrors monthly_trend in queries_ries.py
    # so the "Funding over time" chart can share the same x-axis builder
    # as the Publications "Outputs over time" chart.
    "funding_monthly_trend": f"""
        SELECT
            YEAR(ri.approved_at)                               AS yr,
            MONTH(ri.approved_at)                              AS mo,
            COUNT(ri.ID)                                       AS funded_projects,
            {_ALLOCATED_FUND_EXPR}                              AS total_allocated_fund
        FROM ri_submission ri
        WHERE
            {_FUNDED_FILTER}
            {_YEAR_FILTER}
        GROUP BY yr, mo
        ORDER BY yr ASC, mo ASC;
    """,

    # ----------------------------------------------------------------
    # Funding by agency
    # ----------------------------------------------------------------

    "funding_by_agency": f"""
        SELECT
            COALESCE(ag.agency_name, 'Not Specified')          AS funding_agency,
            COUNT(ri.ID)                                       AS funded_projects,
            {_ALLOCATED_FUND_EXPR}                              AS total_allocated_fund
        FROM ri_submission ri
        LEFT JOIN agencies ag
            ON ri.SourceofFund = ag.id
            AND ag.deleted_at IS NULL
        WHERE
            {_FUNDED_FILTER}
            {_YEAR_FILTER}
        GROUP BY ag.id, ag.agency_name
        ORDER BY total_allocated_fund DESC;
    """,

    # ----------------------------------------------------------------
    # Year-filter dropdown options (ignores :year on purpose — same
    # convention as RIES_QUERIES["year_filter_options"])
    # ----------------------------------------------------------------

    "year_filter_options": f"""
        SELECT DISTINCT
            YEAR(ri.approved_at) AS fiscal_year
        FROM ri_submission ri
        WHERE
            {_FUNDED_FILTER}
        ORDER BY fiscal_year DESC;
    """,
}