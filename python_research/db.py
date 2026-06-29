import os
from contextlib import contextmanager
from typing import Optional, Union

import pymysql
import pymysql.cursors
from dotenv import load_dotenv

# Override pre-exported shell variables so the FastAPI service follows the
# project's .env files consistently when the user restarts uvicorn.
load_dotenv(override=True)


def _normalize_publications_database(name: Optional[str]) -> str:
    """
    Map the known publications DB aliases used in this project to the database
    that is actually queryable in the user's local MySQL instance.
    """
    if not name:
        return "publishedpapers"
    if name in {"publications", "publications.sql"}:
        return "publishedpapers"
    return name

DB_CONFIG = {
    "host": os.getenv("DB_HOST", "127.0.0.1"),
    "port": int(os.getenv("DB_PORT", "3306")),
    "user": os.getenv("DB_USERNAME") or os.getenv("DB_USER", "root"),
    "password": os.getenv("DB_PASSWORD", ""),
    "database": os.getenv("DB_DATABASE") or os.getenv("DB_NAME", "research_warehouse"),
    "charset": "utf8mb4",
    "cursorclass": pymysql.cursors.DictCursor,
}

# Second connection: the publications database.
# Prefer PUBLICATIONS_DB_* so the Python service follows the current .env,
# while still accepting the older RIES_DB_* names as a fallback.
RIES_DB_CONFIG = {
    "host": os.getenv("PUBLICATIONS_DB_HOST") or os.getenv("RIES_DB_HOST", "127.0.0.1"),
    "port": int(os.getenv("PUBLICATIONS_DB_PORT") or os.getenv("RIES_DB_PORT", "3306")),
    "user": os.getenv("PUBLICATIONS_DB_USERNAME") or os.getenv("RIES_DB_USERNAME", "root"),
    "password": os.getenv("PUBLICATIONS_DB_PASSWORD") or os.getenv("RIES_DB_PASSWORD", ""),
    "database": _normalize_publications_database(
        os.getenv("PUBLICATIONS_DB_DATABASE") or os.getenv("RIES_DB_DATABASE", "publishedpapers")
    ),
    "charset": "utf8mb4",
    "cursorclass": pymysql.cursors.DictCursor,
}

# Third connection: the funding database (soulsuedu_ries)
FUNDING_DB_CONFIG = {
    "host": os.getenv("FUNDING_DB_HOST", "127.0.0.1"),
    "port": int(os.getenv("FUNDING_DB_PORT", "3306")),
    "user": os.getenv("FUNDING_DB_USERNAME", "root"),
    "password": os.getenv("FUNDING_DB_PASSWORD", ""),
    "database": os.getenv("FUNDING_DB_DATABASE", "RIES"),
    "charset": "utf8mb4",
    "cursorclass": pymysql.cursors.DictCursor,
}


@contextmanager
def get_connection():
    connection = pymysql.connect(**DB_CONFIG)
    try:
        yield connection
    finally:
        connection.close()


@contextmanager
def get_ries_connection():
    connection = pymysql.connect(**RIES_DB_CONFIG)
    try:
        yield connection
    finally:
        connection.close()


@contextmanager
def get_funding_connection():
    connection = pymysql.connect(**FUNDING_DB_CONFIG)
    try:
        yield connection
    finally:
        connection.close()


def run_query(sql: str, params: Optional[Union[tuple, dict]] = None) -> list[dict]:
    with get_connection() as conn:
        with conn.cursor() as cursor:
            cursor.execute(sql, params)
            return cursor.fetchall()


def run_ries_query(sql: str, params: Optional[Union[tuple, dict]] = None) -> list[dict]:
    """Same as run_query, but against the publications database."""
    with get_ries_connection() as conn:
        with conn.cursor() as cursor:
            cursor.execute(sql, params)
            return cursor.fetchall()


def run_funding_query(sql: str, params: Optional[Union[tuple, dict]] = None) -> list[dict]:
    """Same as run_query, but against the soulsuedu_ries (funding) database."""
    with get_funding_connection() as conn:
        with conn.cursor() as cursor:
            cursor.execute(sql, params)
            return cursor.fetchall()


def check_connection() -> bool:
    try:
        with get_connection() as conn:
            with conn.cursor() as cursor:
                cursor.execute("SELECT 1")
                cursor.fetchone()
        return True
    except Exception:
        return False


def check_ries_connection() -> bool:
    try:
        with get_ries_connection() as conn:
            with conn.cursor() as cursor:
                cursor.execute("SELECT 1")
                cursor.fetchone()
        return True
    except Exception:
        return False


def check_funding_connection() -> bool:
    try:
        with get_funding_connection() as conn:
            with conn.cursor() as cursor:
                cursor.execute("SELECT 1")
                cursor.fetchone()
        return True
    except Exception:
        return False
