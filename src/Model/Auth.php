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
     *
     * Regenerates the session ID first so a session fixed before login
     * (e.g. a cookie set by an attacker before the victim authenticates)
     * doesn't inherit the authenticated state — the pre-login ID is
     * invalidated and a fresh one issued, keeping the session data.
     */
    public static function login(array $row): void
    {
        session_regenerate_id(true);
        $_SESSION['user'] = $row;
    }

    /**
     * Fully end the session (mirrors old `session_unset()` on logout,
     * `index.php:107`, extended to also destroy the session store and
     * clear the cookie so a captured session ID can't be reused after
     * logout).
     */
    public static function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }

        session_destroy();
    }
}
