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

/**
 * Top-level safety net for both front controllers: without this, an
 * uncaught PDOException/TypeError renders PHP's default error output —
 * a raw stack trace including file paths and (for a DB error) the failing
 * SQL — straight to the browser whenever `display_errors` is on (the
 * common default on a dev-leaning XAMPP install this app was never
 * guaranteed to have turned off). Logs the real error server-side and
 * shows a generic page instead.
 */
if (!function_exists('registerErrorHandler')) {
    function registerErrorHandler(): void
    {
        ini_set('display_errors', '0');
        ini_set('log_errors', '1');

        set_exception_handler(function (\Throwable $e): void {
            error_log($e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine() . "\n" . $e->getTraceAsString());

            // Discard whatever partial page (head/nav/etc.) was already
            // buffered before the error hit, so the response is a clean
            // error page instead of a broken half-rendered page with this
            // message glued onto the end of it.
            while (ob_get_level() > 0) {
                ob_end_clean();
            }

            if (!headers_sent()) {
                http_response_code(500);
            }
            echo '<!DOCTYPE html><html lang="vi"><head><meta charset="utf-8">'
                . '<title>Đã xảy ra lỗi</title></head>'
                . '<body style="font-family:sans-serif;text-align:center;padding:60px 20px;">'
                . '<h1>Đã xảy ra lỗi</h1>'
                . '<p>Rất tiếc, hệ thống gặp sự cố. Vui lòng thử lại sau.</p>'
                . '</body></html>';
        });
    }
}
