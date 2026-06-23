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

# Second connection: the RIES publications/outputs database
RIES_DB_CONFIG = {
    "host": os.getenv("RIES_DB_HOST", "127.0.0.1"),
    "port": int(os.getenv("RIES_DB_PORT", "3306")),
    "user": os.getenv("RIES_DB_USERNAME", "root"),
    "password": os.getenv("RIES_DB_PASSWORD", ""),
    "database": os.getenv("RIES_DB_DATABASE", "soulsuedu_ries"),
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


def run_query(sql: str, params: Optional[Union[tuple, dict]] = None) -> list[dict]:
    with get_connection() as conn:
        with conn.cursor() as cursor:
            cursor.execute(sql, params)
            return cursor.fetchall()


def run_ries_query(sql: str, params: Optional[Union[tuple, dict]] = None) -> list[dict]:
    """Same as run_query, but against the soulsuedu_ries database."""
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


def check_ries_connection() -> bool:
    try:
        with get_ries_connection() as conn:
            with conn.cursor() as cursor:
                cursor.execute("SELECT 1")
                cursor.fetchone()
        return True
    except Exception:
        return False