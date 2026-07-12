<?php

namespace Codemoi\Core;

/**
 * Session-stored CSRF token: generate once per session, verify on every
 * state-changing POST, rotate on login/logout. No DB, no dependency —
 * both front controllers (`public/index.php`, `public/admin/index.php`)
 * already run `session_start()` before anything else.
 */
class Csrf
{
    private const SESSION_KEY = '_token';

    /**
     * The current session's token, creating one on first use.
     */
    public static function token(): string
    {
        if (empty($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
        }

        return $_SESSION[self::SESSION_KEY];
    }

    /**
     * A hidden `<input>` carrying the current token, for embedding directly
     * inside a `<form>`.
     */
    public static function field(): string
    {
        return '<input type="hidden" name="_token" value="' . htmlspecialchars(self::token()) . '">';
    }

    /**
     * Timing-safe check of a submitted token against the session's.
     */
    public static function verify(?string $submitted): bool
    {
        return is_string($submitted) && $submitted !== '' && hash_equals(self::token(), $submitted);
    }

    /**
     * Issue a fresh token, invalidating the old one — called after login
     * and logout so a token can't be replayed across an auth-state change.
     */
    public static function rotate(): void
    {
        $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
    }
}
