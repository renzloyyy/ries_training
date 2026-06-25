"""
main.py
FastAPI app that exposes analytics queries as JSON endpoints for the
Laravel dashboard.

Run locally with:
    uvicorn main:app --reload --port 8001
"""

from typing import Optional

from fastapi import FastAPI, HTTPException, Query
from fastapi.middleware.cors import CORSMiddleware

from db import (
    check_connection,
    check_publications_connection,
    check_ries_connection,
    run_publications_query,
    run_query,
    run_ries_query,
)
from queries import QUERIES
from queries_funding import FUNDING_QUERIES
from queries_ries import RIES_QUERIES

app = FastAPI(
    title="Research Analytics API",
    description="Read-only analytics endpoints backing the research dashboard.",
    version="2.0.0",
)

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["GET"],
    allow_headers=["*"],
)


def _execute(query_key: str, year: Optional[int] = None) -> list[dict]:
    """Run a named proposal query and normalize database errors to HTTP 500."""
    sql = QUERIES.get(query_key)
    if sql is None:
        raise HTTPException(status_code=404, detail=f"Unknown query '{query_key}'")
    try:
        return run_query(sql, {"year": year})
    except Exception as exc:
        raise HTTPException(status_code=500, detail=f"Database error: {exc}")


def _execute_publications(query_key: str, year: Optional[int] = None) -> list[dict]:
    """Run a named publications query against the clean_publications database."""
    sql = RIES_QUERIES.get(query_key)
    if sql is None:
        raise HTTPException(status_code=404, detail=f"Unknown query '{query_key}'")
    try:
        return run_publications_query(sql, {"year": year})
    except Exception as exc:
        raise HTTPException(status_code=500, detail=f"Database error: {exc}")


def _execute_funding(query_key: str, year: Optional[int] = None) -> list[dict]:
    """Run a named funding query against the legacy RIES/funding database."""
    sql = FUNDING_QUERIES.get(query_key)
    if sql is None:
        raise HTTPException(status_code=404, detail=f"Unknown query '{query_key}'")
    try:
        return run_ries_query(sql, {"year": year})
    except Exception as exc:
        raise HTTPException(status_code=500, detail=f"Database error: {exc}")


@app.get("/api/health")
def health():
    """Simple health check — verifies all three dashboard connections."""
    proposals_ok = check_connection()
    publications_ok = check_publications_connection()
    funding_ok = check_ries_connection()
    return {
        "status": "ok" if (proposals_ok and publications_ok and funding_ok) else "db_unreachable",
        "research_warehouse": proposals_ok,
        "publications": publications_ok,
        "funding_ries": funding_ok,
    }


# ---------------------------------------------------------------------------
# Proposals (research_warehouse) endpoints
# ---------------------------------------------------------------------------

@app.get("/api/proposals/status-distribution")
def status_distribution(year: Optional[int] = Query(default=None, ge=2000, le=2100)):
    """Total proposals grouped by status_code."""
    return _execute("status_distribution", year)


@app.get("/api/proposals/by-campus")
def proposals_by_campus(year: Optional[int] = Query(default=None, ge=2000, le=2100)):
    """Total proposals grouped by campus."""
    return _execute("proposals_by_campus", year)


@app.get("/api/proposals/by-program")
def proposals_by_program(year: Optional[int] = Query(default=None, ge=2000, le=2100)):
    """Total proposals grouped by program."""
    return _execute("proposals_by_program", year)


@app.get("/api/proposals/by-year")
def proposals_by_year(year: Optional[int] = Query(default=None, ge=2000, le=2100)):
    """Total proposals grouped by submission year."""
    return _execute("proposals_by_year", year)


@app.get("/api/proposals/by-quarter")
def proposals_by_quarter(year: Optional[int] = Query(default=None, ge=2000, le=2100)):
    """Total proposals grouped by submission year + quarter."""
    return _execute("proposals_by_quarter", year)


@app.get("/api/proposals/by-month")
def proposals_by_month(year: Optional[int] = Query(default=None, ge=2000, le=2100)):
    """Total proposals grouped by submission year + month."""
    return _execute("proposals_by_month", year)


@app.get("/api/proposals/by-format")
def proposals_by_format(year: Optional[int] = Query(default=None, ge=2000, le=2100)):
    """Total proposals grouped by research format."""
    return _execute("proposals_by_format", year)


@app.get("/api/proposals/by-sdg")
def proposals_by_sdg(year: Optional[int] = Query(default=None, ge=2000, le=2100)):
    """Total proposals grouped by SDG."""
    return _execute("proposals_by_sdg", year)


@app.get("/api/proposals/by-agenda")
def proposals_by_agenda(year: Optional[int] = Query(default=None, ge=2000, le=2100)):
    """Total proposals grouped by research agenda."""
    return _execute("proposals_by_agenda", year)


@app.get("/api/proposals/approval-rate")
def approval_rate_overall(year: Optional[int] = Query(default=None, ge=2000, le=2100)):
    """Single overall approval rate percentage."""
    rows = _execute("approval_rate_overall", year)
    return rows[0] if rows else {"approval_rate_percentage": None}


@app.get("/api/proposals/approval-rate-by-campus")
def approval_rate_by_campus(year: Optional[int] = Query(default=None, ge=2000, le=2100)):
    """Approval rate percentage broken down by campus."""
    return _execute("approval_rate_by_campus", year)


@app.get("/api/proposals/campus-program-breakdown")
def campus_program_breakdown(year: Optional[int] = Query(default=None, ge=2000, le=2100)):
    """Total proposals grouped by campus + program."""
    return _execute("campus_program_breakdown", year)


@app.get("/api/proposals/campus-sdg-breakdown")
def campus_sdg_breakdown(year: Optional[int] = Query(default=None, ge=2000, le=2100)):
    """Total proposals grouped by campus + SDG."""
    return _execute("campus_sdg_breakdown", year)


@app.get("/api/proposals/dashboard")
def dashboard_summary(year: Optional[int] = Query(default=None, ge=2000, le=2100)):
    """
    Bundle every proposals chart's data into a single response so the
    Laravel side can populate the Proposals panel with one HTTP call.
    """
    return {key: _execute(key, year) for key in QUERIES.keys()}


# ---------------------------------------------------------------------------
# Publications — clean_publications endpoints
# ---------------------------------------------------------------------------

_PUB_KEYS = [
    "pub_by_year",
    "pub_monthly_trend",
    "pub_quarterly_trend",
    "pub_by_campus",
    "pub_campus_contribution",
    "pub_by_indexing_tier",
    "pub_campus_indexing",
    "pub_year_campus",
    "pub_top_journals",
    "pub_average_pages",
    "pub_data_quality",
]


def _first_row(rows: list[dict]) -> dict:
    """Return the first row or an empty dict for single-row KPI queries."""
    return rows[0] if rows else {}


def _build_legacy_publications_dashboard(year: Optional[int]) -> dict:
    """
    Keep /api/publications/dashboard compatible with the existing Laravel UI
    while sourcing the data from clean_publications instead of ri_submission.

    Some legacy widgets have no true clean_publications equivalent
    (program, SDG, research format), so those are returned as empty arrays
    rather than inventing incorrect values.
    """
    summary = _first_row(_execute_publications("pub_summary_kpis", None))
    by_campus = _execute_publications("pub_by_campus", year)
    by_year = _execute_publications("pub_by_year", None)
    monthly = _execute_publications("pub_monthly_trend", year)
    top_journals = _execute_publications("pub_top_journals", year)
    indexing = _execute_publications("pub_by_indexing_tier", year)

    total_publications = int(summary.get("total_publications") or 0)
    total_campuses = int(summary.get("total_campuses") or 0)

    # The real publications database does not store proposal-stage fields
    # such as program, SDG, or publication workflow status. These fallback
    # structures keep the current Laravel widgets rendering with truthful
    # published-paper aggregates instead of crashing on missing keys.
    derived_categories = [
        {
            "category": "Journal",
            "total_outputs": total_publications,
        }
    ] if total_publications else []

    derived_formats = [
        {
            "research_format": row.get("indexing_tier") or "Unspecified",
            "total_outputs": int(row.get("total") or 0),
        }
        for row in indexing
    ]

    derived_program_rows = [
        {
            "campus_name": row.get("campus"),
            "program_name": "All publications",
            "total_outputs": int(row.get("total_publications") or 0),
            "completed_outputs": int(row.get("total_publications") or 0),
        }
        for row in by_campus
    ]

    return {
        "total_outputs": [{"total_outputs": total_publications}],
        "completed_outputs": [{"completed_outputs": total_publications}],
        "completion_rate": [{
            "total_outputs": total_publications,
            "completed_outputs": total_publications,
            "completion_rate_pct": 100.0 if total_publications else None,
        }],
        "active_campuses": [{"active_campuses": total_campuses}],
        "outputs_by_campus": [
            {
                "campus_name": row.get("campus"),
                "total_outputs": int(row.get("total_publications") or 0),
                "completed_outputs": int(row.get("total_publications") or 0),
                "pending_outputs": 0,
            }
            for row in by_campus
        ],
        "outputs_by_program": derived_program_rows,
        "outputs_by_category": derived_categories,
        "outputs_by_format": derived_formats,
        "outputs_by_paper_status": [{
            "paper_status": "Published",
            "total_outputs": total_publications,
        }] if total_publications else [],
        "outputs_by_sdg": [],
        "year_filter_options": _execute_publications("pub_year_filter_options", None),
        "monthly_trend": [
            {
                "yr": row.get("year_published"),
                "mo": row.get("month_published"),
                "total_outputs": int(row.get("total") or 0),
            }
            for row in monthly
        ],
        "yearly_trend": [
            {
                "yr": row.get("year_published"),
                "total_outputs": int(row.get("total_publications") or 0),
                "completed_outputs": int(row.get("total_publications") or 0),
            }
            for row in by_year
        ],
        "top_journals": top_journals,
        "indexing_tiers": indexing,
    }


@app.get("/api/publications/summary")
def publications_summary():
    """
    Seven KPI values for the clean_publications dataset.
    This call is intentionally year-agnostic so the top totals reflect the
    full imported publications database.
    """
    rows = _execute_publications("pub_summary_kpis", None)
    return rows[0] if rows else {}


@app.get("/api/publications/years")
def publications_years():
    """Distinct publication years (DESC) for the filter dropdown."""
    return _execute_publications("pub_year_filter_options", None)


@app.get("/api/publications/by-year")
def publications_by_year():
    """Annual publication totals across the full clean_publications history."""
    return _execute_publications("pub_by_year", None)


@app.get("/api/publications/monthly-trend")
def publications_monthly_trend(year: Optional[int] = Query(default=None, ge=2000, le=2100)):
    """Monthly publication counts, optionally filtered to a single year."""
    return _execute_publications("pub_monthly_trend", year)


@app.get("/api/publications/by-campus")
def publications_by_campus(year: Optional[int] = Query(default=None, ge=2000, le=2100)):
    """Publication counts per campus."""
    return _execute_publications("pub_by_campus", year)


@app.get("/api/publications/by-indexing-tier")
def publications_by_indexing_tier(year: Optional[int] = Query(default=None, ge=2000, le=2100)):
    """Publication counts and percentage share by indexing tier."""
    return _execute_publications("pub_by_indexing_tier", year)


@app.get("/api/publications/campus-indexing")
def publications_campus_indexing(year: Optional[int] = Query(default=None, ge=2000, le=2100)):
    """Campus × indexing-tier grouped breakdown."""
    return _execute_publications("pub_campus_indexing", year)


@app.get("/api/publications/year-campus")
def publications_year_campus():
    """Year × campus cross-tab across the full publication history."""
    return _execute_publications("pub_year_campus", None)


@app.get("/api/publications/top-journals")
def publications_top_journals(year: Optional[int] = Query(default=None, ge=2000, le=2100)):
    """Top 10 journals by publication count."""
    return _execute_publications("pub_top_journals", year)


@app.get("/api/publications/average-pages")
def publications_average_pages(year: Optional[int] = Query(default=None, ge=2000, le=2100)):
    """Average page count across publications with a parsed page value."""
    rows = _execute_publications("pub_average_pages", year)
    return rows[0] if rows else {"average_pages": None}


@app.get("/api/publications/data-quality")
def publications_data_quality():
    """Dataset-quality flag summary across clean_publications."""
    rows = _execute_publications("pub_data_quality", None)
    return rows[0] if rows else {}


@app.get("/api/publications/growth-rate")
def publications_growth_rate():
    """Year-over-year publication growth across all years on record."""
    return _execute_publications("pub_growth_rate", None)


@app.get("/api/publications/quarterly-trend")
def publications_quarterly_trend(year: Optional[int] = Query(default=None, ge=2000, le=2100)):
    """Quarterly publication counts, optionally filtered to a single year."""
    return _execute_publications("pub_quarterly_trend", year)


@app.get("/api/publications/campus-contribution")
def publications_campus_contribution(year: Optional[int] = Query(default=None, ge=2000, le=2100)):
    """Campus publication totals with percentage contribution."""
    return _execute_publications("pub_campus_contribution", year)


@app.get("/api/publications/dashboard")
def publications_dashboard(year: Optional[int] = Query(default=None, ge=2000, le=2100)):
    """
    Compatibility dashboard payload for the existing Laravel publications
    panel. The underlying data now comes from clean_publications, but the
    JSON shape stays close to the legacy frontend contract.
    """
    return _build_legacy_publications_dashboard(year)


# ---------------------------------------------------------------------------
# Funding (legacy RIES/funding database) endpoints
# ---------------------------------------------------------------------------

@app.get("/api/funding/total-projects")
def funding_total_projects(year: Optional[int] = Query(default=None, ge=2000, le=2100)):
    rows = _execute_funding("total_funded_projects", year)
    return rows[0] if rows else {"total_funded_projects": 0}


@app.get("/api/funding/total-allocated")
def funding_total_allocated(year: Optional[int] = Query(default=None, ge=2000, le=2100)):
    rows = _execute_funding("total_allocated_fund", year)
    return rows[0] if rows else {"total_allocated_fund": 0}


@app.get("/api/funding/by-campus")
def funding_by_campus(year: Optional[int] = Query(default=None, ge=2000, le=2100)):
    return _execute_funding("funding_by_campus", year)


@app.get("/api/funding/by-category")
def funding_by_category(year: Optional[int] = Query(default=None, ge=2000, le=2100)):
    """Category-only funding breakdown."""
    return _execute_funding("funding_by_category", year)


@app.get("/api/funding/by-format")
def funding_by_format(year: Optional[int] = Query(default=None, ge=2000, le=2100)):
    """Funding grouped by category and research format."""
    return _execute_funding("funding_by_format", year)


@app.get("/api/funding/by-department")
def funding_by_department(year: Optional[int] = Query(default=None, ge=2000, le=2100)):
    return _execute_funding("funding_by_department", year)


@app.get("/api/funding/by-year")
def funding_by_year(year: Optional[int] = Query(default=None, ge=2000, le=2100)):
    return _execute_funding("funding_by_year", year)


@app.get("/api/funding/by-quarter")
def funding_by_quarter(year: Optional[int] = Query(default=None, ge=2000, le=2100)):
    return _execute_funding("funding_by_quarter", year)


@app.get("/api/funding/monthly-trend")
def funding_monthly_trend(year: Optional[int] = Query(default=None, ge=2000, le=2100)):
    return _execute_funding("funding_monthly_trend", year)


@app.get("/api/funding/by-agency")
def funding_by_agency(year: Optional[int] = Query(default=None, ge=2000, le=2100)):
    return _execute_funding("funding_by_agency", year)


@app.get("/api/funding/years")
def funding_years():
    """Distinct fiscal years for the funding filter dropdown."""
    return _execute_funding("year_filter_options", None)


@app.get("/api/funding/dashboard")
def funding_dashboard(year: Optional[int] = Query(default=None, ge=2000, le=2100)):
    """
    Bundle the funding panel data into one response.
    Year filter options stay on the dedicated /api/funding/years endpoint.
    """
    return {
        key: _execute_funding(key, year)
        for key in FUNDING_QUERIES.keys()
        if key != "year_filter_options"
    }
