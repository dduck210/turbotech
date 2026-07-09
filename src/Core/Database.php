<?php

namespace Codemoi\Core;

use PDO;
use PDOException;

/**
 * Thin PDO wrapper centralizing the database helpers previously scattered
 * across `model/pdo.php` as procedural functions. Ported verbatim (same
 * prepared-statement usage, same per-call connection lifetime) so behavior
 * matches the legacy code exactly.
 */
class Database
{
    /**
     * Open a new PDO connection using the settings from Config.
     * Mirrors the old `pdo_get_connection()` — one connection per call.
     */
    private static function connection(): PDO
    {
        $dsn = 'mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME . ';charset=' . Config::CHARSET;

        $conn = new PDO($dsn, Config::DB_USER, Config::DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $conn;
    }

    /**
     * Run a SELECT and return all matching rows.
     * Mirrors old `pdo_query()`.
     *
     * @param string $sql
     * @param mixed ...$args
     * @return array
     * @throws PDOException
     */
    public static function query(string $sql, ...$args): array
    {
        try {
            $conn = self::connection();
            $stmt = $conn->prepare($sql);
            $stmt->execute($args);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw $e;
        } finally {
            unset($conn);
        }
    }

    /**
     * Run a SELECT and return the first matching row (associative array).
     * Mirrors old `pdo_query_one()`.
     *
     * @param string $sql
     * @param mixed ...$args
     * @return array|false
     * @throws PDOException
     */
    public static function queryOne(string $sql, ...$args)
    {
        try {
            $conn = self::connection();
            $stmt = $conn->prepare($sql);
            $stmt->execute($args);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw $e;
        } finally {
            unset($conn);
        }
    }

    /**
     * Run a SELECT and return the first column of the first row.
     * Mirrors old `pdo_query_value()`.
     *
     * @param string $sql
     * @param mixed ...$args
     * @return mixed
     * @throws PDOException
     */
    public static function queryValue(string $sql, ...$args)
    {
        try {
            $conn = self::connection();
            $stmt = $conn->prepare($sql);
            $stmt->execute($args);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return array_values($row)[0];
        } catch (PDOException $e) {
            throw $e;
        } finally {
            unset($conn);
        }
    }

    /**
     * Run an INSERT/UPDATE/DELETE statement, returning the affected row
     * count (useful for conditional UPDATEs where the caller needs to know
     * whether a row actually matched, e.g. an ownership/status-guarded
     * cancellation). Mirrors old `pdo_execute()`, extended with a return
     * value — existing callers that ignore the return are unaffected.
     *
     * @param string $sql
     * @param mixed ...$args
     * @return int Number of affected rows.
     * @throws PDOException
     */
    public static function execute(string $sql, ...$args): int
    {
        try {
            $conn = self::connection();
            $stmt = $conn->prepare($sql);
            $stmt->execute($args);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw $e;
        } finally {
            unset($conn);
        }
    }

    /**
     * Run an INSERT statement and return the last insert id.
     * Mirrors old `pdo_execute_return_lastInsertId()`.
     *
     * @param string $sql
     * @param mixed ...$args
     * @return string
     * @throws PDOException
     */
    public static function executeReturnId(string $sql, ...$args): string
    {
        try {
            $conn = self::connection();
            $stmt = $conn->prepare($sql);
            $stmt->execute($args);
            return $conn->lastInsertId();
        } catch (PDOException $e) {
            throw $e;
        } finally {
            unset($conn);
        }
    }
}
