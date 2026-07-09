<?php

namespace Codemoi\Core;

/**
 * Central holder for database connection settings.
 * Values must stay in sync with the legacy `model/pdo.php:8` connection string
 * until the old procedural layer is fully retired.
 */
class Config
{
    const DB_HOST = 'localhost';
    const DB_NAME = 'codemoi2';
    const DB_USER = 'root';
    const DB_PASS = '';
    const CHARSET = 'utf8';
}
