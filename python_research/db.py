"""
db.py
Handles the MySQL connection (PyMySQL) and a small helper to run
read-only SQL queries and return the results as a list of dicts.

Configuration is read from environment variables (see .env.example).
"""

import os
from contextlib import contextmanager

import pymysql
import pymysql.cursors
from dotenv import load_dotenv

load_dotenv()

DB_CONFIG = {
    "host": os.getenv("DB_HOST", "127.0.0.1"),
    "port": int(os.getenv("DB_PORT", "3306")),
    "user": os.getenv("DB_USER", "root"),
    "password": os.getenv("DB_PASSWORD", ""),
    "database": os.getenv("DB_NAME", "research_warehouse"),
    "charset": "utf8mb4",
    "cursorclass": pymysql.cursors.DictCursor,
}


@contextmanager
def get_connection():
    """
    Opens a new PyMySQL connection and guarantees it is closed afterwards.
    Kept simple (one connection per request) since this API is read-only
    and meant to be called occasionally by the Laravel backend. If traffic
    grows, swap this for a DBUtils.PooledDB pool without changing callers.
    """
    connection = pymysql.connect(**DB_CONFIG)
    try:
        yield connection
    finally:
        connection.close()


def run_query(sql: str, params: tuple | dict | None = None) -> list[dict]:
    """
    Executes a SELECT statement and returns the rows as a list of dicts.
    """
    with get_connection() as conn:
        with conn.cursor() as cursor:
            cursor.execute(sql, params)
            rows = cursor.fetchall()
    return rows


def check_connection() -> bool:
    """Used by the /api/health endpoint to confirm the DB is reachable."""
    try:
        with get_connection() as conn:
            with conn.cursor() as cursor:
                cursor.execute("SELECT 1")
                cursor.fetchone()
        return True
    except Exception:
        return False