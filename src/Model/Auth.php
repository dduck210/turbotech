<?php

namespace Codemoi\Model;

/**
 * Thin wrapper around `$_SESSION['user']`. Assumes the front controller has
 * already called `session_start()`.
 */
class Auth
{
    /**
     * Currently logged-in user row, or null when logged out.
     *
     * @return array|null
     */
    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    /**
     * Whether a user is currently logged in.
     */
    public static function check(): bool
    {
        return isset($_SESSION['user']) && is_array($_SESSION['user']);
    }

    /**
     * Store the authenticated user row in the session.
     * Mirrors old inline `$_SESSION['user'] = $check_user;` (`index.php:96`).
     */
    public static function login(array $row): void
    {
        $_SESSION['user'] = $row;
    }

    /**
     * Clear the whole session (mirrors old `session_unset()` on logout,
     * `index.php:107`).
     */
    public static function logout(): void
    {
        session_unset();
    }
}
