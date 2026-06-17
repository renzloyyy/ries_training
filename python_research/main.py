"""
main.py
FastAPI app that exposes each analytics query as a JSON endpoint.
Meant to be consumed by a Laravel backend (e.g. via the Http facade),
which then feeds the data into Blade views built on the Sneat template.

Run locally with:
    uvicorn main:app --reload --port 8001
"""

from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware

from db import run_query, check_connection
from queries import QUERIES

app = FastAPI(
    title="Research Proposal Analytics API",
    description="Read-only analytics endpoints backing the research dashboard.",
    version="1.0.0",
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


def _execute(query_key: str) -> list[dict]:
    """Shared helper: run a named query and turn DB errors into HTTP 500s."""
    sql = QUERIES.get(query_key)
    if sql is None:
        raise HTTPException(status_code=404, detail=f"Unknown query '{query_key}'")
    try:
        return run_query(sql)
    except Exception as exc:
        raise HTTPException(status_code=500, detail=f"Database error: {exc}")


@app.get("/api/health")
def health():
    """Simple health check, also verifies the DB is reachable."""
    db_ok = check_connection()
    return {"status": "ok" if db_ok else "db_unreachable", "database": db_ok}


@app.get("/api/proposals/status-distribution")
def status_distribution():
    """Total proposals grouped by status_code."""
    return _execute("status_distribution")


@app.get("/api/proposals/by-campus")
def proposals_by_campus():
    """Total proposals grouped by campus."""
    return _execute("proposals_by_campus")


@app.get("/api/proposals/by-program")
def proposals_by_program():
    """Total proposals grouped by program."""
    return _execute("proposals_by_program")


@app.get("/api/proposals/by-year")
def proposals_by_year():
    """Total proposals grouped by submission year."""
    return _execute("proposals_by_year")


@app.get("/api/proposals/by-quarter")
def proposals_by_quarter():
    """Total proposals grouped by submission year + quarter."""
    return _execute("proposals_by_quarter")


@app.get("/api/proposals/by-month")
def proposals_by_month():
    """Total proposals grouped by submission year + month."""
    return _execute("proposals_by_month")


@app.get("/api/proposals/by-format")
def proposals_by_format():
    """Total proposals grouped by research format."""
    return _execute("proposals_by_format")


@app.get("/api/proposals/by-sdg")
def proposals_by_sdg():
    """Total proposals grouped by SDG."""
    return _execute("proposals_by_sdg")


@app.get("/api/proposals/by-agenda")
def proposals_by_agenda():
    """Total proposals grouped by research agenda."""
    return _execute("proposals_by_agenda")


@app.get("/api/proposals/approval-rate")
def approval_rate_overall():
    """Single overall approval rate percentage."""
    rows = _execute("approval_rate_overall")
    return rows[0] if rows else {"approval_rate_percentage": None}


@app.get("/api/proposals/approval-rate-by-campus")
def approval_rate_by_campus():
    """Approval rate percentage broken down by campus."""
    return _execute("approval_rate_by_campus")


@app.get("/api/proposals/campus-program-breakdown")
def campus_program_breakdown():
    """Total proposals grouped by campus + program."""
    return _execute("campus_program_breakdown")


@app.get("/api/proposals/campus-sdg-breakdown")
def campus_sdg_breakdown():
    """Total proposals grouped by campus + SDG."""
    return _execute("campus_sdg_breakdown")


@app.get("/api/proposals/dashboard")
def dashboard_summary():
    """
    Convenience endpoint that bundles every chart's data into a single
    response, so the Laravel controller can populate a whole dashboard
    page with one HTTP call instead of thirteen.
    """
    return {key: _execute(key) for key in QUERIES.keys()}