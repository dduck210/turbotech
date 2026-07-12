<?php

/**
 * Global escaping helper for view templates (plain PHP includes, not
 * autoloaded classes — a bare function is the least friction at ~40 call
 * sites across view/ and admin/view/). Output-time HTML-escaping is the
 * actual XSS defense; this wraps every DB- or user-sourced value echoed
 * into HTML context. Leave `json_encode(...)` echoes into `<script>`
 * blocks alone — that's a different, already-correct escaping mechanism
 * for JS context.
 */
if (!function_exists('e')) {
    function e($value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}
