import os
from contextlib import contextmanager
from typing import Optional, Union

import pymysql
import pymysql.cursors
from dotenv import load_dotenv

load_dotenv()

DB_CONFIG = {
    "host": os.getenv("DB_HOST", "127.0.0.1"),
    "port": int(os.getenv("DB_PORT", "3306")),
    "user": os.getenv("DB_USERNAME") or os.getenv("DB_USER", "root"),
    "password": os.getenv("DB_PASSWORD", ""),
    "database": os.getenv("DB_DATABASE") or os.getenv("DB_NAME", "research_warehouse"),
    "charset": "utf8mb4",
    "cursorclass": pymysql.cursors.DictCursor,
}

# Publications now use their own connection details so the clean_publications
# dataset can live in a separate database from the proposal/funding pipeline.
PUBLICATIONS_DB_CONFIG = {
    "host": os.getenv("PUBLICATIONS_DB_HOST", os.getenv("RIES_DB_HOST", "127.0.0.1")),
    "port": int(os.getenv("PUBLICATIONS_DB_PORT", os.getenv("RIES_DB_PORT", "3306"))),
    "user": os.getenv("PUBLICATIONS_DB_USERNAME", os.getenv("RIES_DB_USERNAME", "root")),
    "password": os.getenv("PUBLICATIONS_DB_PASSWORD", os.getenv("RIES_DB_PASSWORD", "")),
    "database": (
        os.getenv("PUBLICATIONS_DB_DATABASE")
        or os.getenv("PUBLICATIONS_DB_NAME")
        or os.getenv("RIES_DB_DATABASE")
        or "publishedpapers"
    ),
    "charset": "utf8mb4",
    "cursorclass": pymysql.cursors.DictCursor,
}

# Funding keeps the legacy RIES-style connection because those queries still
# target the proposal/funding warehouse tables rather than clean_publications.
RIES_DB_CONFIG = {
    "host": os.getenv("RIES_DB_HOST", "127.0.0.1"),
    "port": int(os.getenv("RIES_DB_PORT", "3306")),
    "user": os.getenv("RIES_DB_USERNAME", "root"),
    "password": os.getenv("RIES_DB_PASSWORD", ""),
    "database": os.getenv("RIES_DB_DATABASE", "RIES"),
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
def get_publications_connection():
    connection = pymysql.connect(**PUBLICATIONS_DB_CONFIG)
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


def run_query(sql: str, params: Optional[Union[tuple, dict]] = None) -> list[dict]:
    with get_connection() as conn:
        with conn.cursor() as cursor:
            cursor.execute(sql, params)
            return cursor.fetchall()


def run_publications_query(sql: str, params: Optional[Union[tuple, dict]] = None) -> list[dict]:
    """Run a query against the clean_publications database connection."""
    with get_publications_connection() as conn:
        with conn.cursor() as cursor:
            cursor.execute(sql, params)
            return cursor.fetchall()


def run_ries_query(sql: str, params: Optional[Union[tuple, dict]] = None) -> list[dict]:
    """Run a query against the legacy funding / RIES connection."""
    with get_ries_connection() as conn:
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


def check_publications_connection() -> bool:
    try:
        with get_publications_connection() as conn:
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
