"""
main.py
FastAPI app that exposes each analytics query as a JSON endpoint.
Meant to be consumed by a Laravel backend (e.g. via the Http facade),
which then feeds the data into Blade views built on the Sneat template.

Run locally with:
    uvicorn main:app --reload --port 8001
"""

from typing import Optional

from fastapi import FastAPI, HTTPException, Query
from fastapi.middleware.cors import CORSMiddleware

from db import (
    run_query, check_connection,
    run_ries_query, check_ries_connection,
    run_funding_query, check_funding_connection,
)
from queries import QUERIES
from queries_ries import RIES_QUERIES
from queries_funding import FUNDING_QUERIES

app = FastAPI(
    title="Research Analytics API",
    description="Read-only analytics endpoints backing the research dashboard.",
    version="2.0.0",
)

# Allow the Laravel app to call this API from the browser/server.
# Replace "*" with your actual Laravel app URL(s) before going to production,
# e.g. ["http://localhost:8000", "https://research.yourdomain.com"]
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["GET"],
    allow_headers=["*"],
)


# ---------------------------------------------------------------------------
# Shared helpers
# ---------------------------------------------------------------------------

def _execute(query_key: str, year: Optional[int] = None) -> list[dict]:
    """Run a named query against the proposals warehouse; map DB errors to HTTP 500."""
    sql = QUERIES.get(query_key)
    if sql is None:
        raise HTTPException(status_code=404, detail=f"Unknown query '{query_key}'")
    try:
        return run_query(sql, {"year": year})
    except Exception as exc:
        raise HTTPException(status_code=500, detail=f"Database error: {exc}")


def _execute_ries(query_key: str, year: Optional[int] = None) -> list[dict]:
    """Run a named query against published_paper; map DB errors to HTTP 500."""
    sql = RIES_QUERIES.get(query_key)
    if sql is None:
        raise HTTPException(status_code=404, detail=f"Unknown query '{query_key}'")
    try:
        return run_ries_query(sql, {"year": year})
    except Exception as exc:
        raise HTTPException(status_code=500, detail=f"Database error: {exc}")


def _execute_funding(query_key: str, year: Optional[int] = None) -> list[dict]:
    """Run a named funding query against soulsuedu_ries; map DB errors to HTTP 500."""
    sql = FUNDING_QUERIES.get(query_key)
    if sql is None:
        raise HTTPException(status_code=404, detail=f"Unknown query '{query_key}'")
    try:
        return run_funding_query(sql, {"year": year})
    except Exception as exc:
        raise HTTPException(status_code=500, detail=f"Database error: {exc}")


# ---------------------------------------------------------------------------
# Health check
# ---------------------------------------------------------------------------

@app.get("/api/health")
def health():
    """Simple health check — verifies all three databases are reachable."""
    db_ok = check_connection()
    ries_ok = check_ries_connection()
    funding_ok = check_funding_connection()
    return {
        "status": "ok" if (db_ok and ries_ok and funding_ok) else "db_unreachable",
        "research_warehouse": db_ok,
        "ries": ries_ok,
        "funding": funding_ok,
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
    Bundles every proposals chart's data into a single response so the
    Laravel controller can populate the whole Proposals panel with one HTTP call.
    """
    return {key: _execute(key, year) for key in QUERIES.keys()}


# ---------------------------------------------------------------------------
# Publications — research_publications dataset (publications database)
# ---------------------------------------------------------------------------

# These endpoints currently target the `research_publications` table, and the
# date normalization is handled inside queries_ries.py because the source stores
# publication dates as free-text month/year values.

_PUB_KEYS = [
    "pub_by_year_clean",
    "pub_monthly_all",
    "pub_campus_contribution",
    "pub_by_indexing_clean",
    "pub_campus_indexing_clean",
    "pub_year_campus_clean",
    "pub_top_journals_clean",
    "pub_quarterly_clean",
    "pub_data_quality",
]
"""Query keys bundled by the /dashboard endpoint (excludes year-filter and
summary KPIs, which are fetched separately or ignore the year param)."""


@app.get("/api/publications/summary")
def publications_summary():
    """Headline KPI row: total, campuses, Scopus %, International %."""
    rows = _execute_ries("pub_summary_kpis", None)
    return rows[0] if rows else {}


@app.get("/api/publications/by-year")
def publications_by_year():
    """Annual totals — year-0/NULL excluded."""
    return _execute_ries("pub_by_year_clean", None)


@app.get("/api/publications/yoy-growth")
def publications_yoy_growth():
    """Year-over-year growth percentage per year."""
    return _execute_ries("pub_yoy_growth", None)


@app.get("/api/publications/campus-contribution")
def publications_campus_contribution():
    """Campus totals with contribution % of grand total."""
    return _execute_ries("pub_campus_contribution", None)


@app.get("/api/publications/monthly-all")
def publications_monthly_all():
    """Monthly counts across all years (no year filter)."""
    return _execute_ries("pub_monthly_all", None)


@app.get("/api/publications/campus-indexing")
def publications_campus_indexing():
    """Campus × indexing-tier cross-tab."""
    return _execute_ries("pub_campus_indexing_clean", None)


@app.get("/api/publications/top-journals")
def publications_top_journals():
    """Top 10 journals by paper count."""
    return _execute_ries("pub_top_journals_clean", None)


@app.get("/api/publications/by-indexing-tier")
def publications_by_indexing_tier():
    """Indexing tier counts + % share."""
    return _execute_ries("pub_by_indexing_clean", None)


@app.get("/api/publications/year-campus")
def publications_year_campus():
    """Year × campus for stacked bar (year-0 excluded)."""
    return _execute_ries("pub_year_campus_clean", None)


@app.get("/api/publications/quarterly")
def publications_quarterly():
    """Quarterly breakdown 2020–2026."""
    return _execute_ries("pub_quarterly_clean", None)


@app.get("/api/publications/data-quality")
def publications_data_quality():
    """Data-quality flag counts from the full clean_publications table."""
    rows = _execute_ries("pub_data_quality", None)
    return rows[0] if rows else {}


@app.get("/api/publications/dashboard")
def publications_dashboard(year: Optional[int] = Query(default=None, ge=2000, le=2100)):
    """
    Bundles all filterable publications chart data into one response.
    Excludes: pub_summary_kpis, pub_by_year, pub_year_campus, pub_data_quality,
    and pub_year_filter_options — those are either year-agnostic or fetched
    separately. The Laravel controller should call /api/publications/summary
    and /api/publications/by-year once on page load, then hit this endpoint
    whenever the year filter changes.
    """
    return {key: _execute_ries(key, year) for key in _PUB_KEYS}


# ---------------------------------------------------------------------------
# Funding (soulsuedu_ries) endpoints
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
    """Category-only breakdown — used by the category donut chart."""
    return _execute_funding("funding_by_category", year)


@app.get("/api/funding/by-format")
def funding_by_format(year: Optional[int] = Query(default=None, ge=2000, le=2100)):
    """Category + research-format breakdown — used by the format bar chart."""
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
    """Distinct fiscal years — populates the year-filter dropdown."""
    return _execute_funding("year_filter_options", None)


@app.get("/api/funding/dashboard")
def funding_dashboard(year: Optional[int] = Query(default=None, ge=2000, le=2100)):
    """
    Bundles every funding chart's data into a single response so the
    Laravel controller can populate the whole Funding panel with one HTTP call.
    """
    return {
        key: _execute_funding(key, year)
        for key in FUNDING_QUERIES.keys()
        if key != "year_filter_options"
    }
