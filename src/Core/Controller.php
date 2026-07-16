<?php

namespace Codemoi\Core;

/**
 * Base class for all controllers. Provides the two helpers every old
 * `index.php` case relied on: rendering a view template and redirecting
 * via a `Location` header.
 */
abstract class Controller
{
    /**
     * Render a view template, delegating to `Core\View::render`.
     *
     * @param string $template Template path relative to `$baseDir`, e.g. `'product/product-list'`.
     * @param array $data Variables to expose to the template.
     * @param string $baseDir Defaults to the client `view/` dir; admin controllers
     *                        pass `'admin/view'` (see `Controller\Admin\AdminController`).
     */
    protected function view(string $template, array $data = [], string $baseDir = 'view'): void
    {
        View::render($template, $data, $baseDir);
    }

    /**
     * Redirect the client to another URL and stop execution.
     *
     * Mirrors the old `header('Location: ...')` calls scattered across
     * `index.php` (e.g. lines 100, 111, 151, 172), but unlike several of
     * those old call sites, this helper always calls `exit` right after
     * sending the header. PHP does NOT stop script execution just because
     * a `Location` header was sent — omitting `exit` (as the old code
     * sometimes does) lets code after the redirect keep running, which is
     * a latent bug. This base class does it correctly; back-porting `exit`
     * into the old procedural code is out of scope for this phase.
     */
    protected function redirect(string $url): void
    {
        // public/index.php buffers the whole page (ob_start()) so it can
        // stamp per-page SEO tags into head.php's placeholder tokens after
        // the router dispatches — but a redirect exits before that
        // replacement step ever runs, so without discarding the buffer here
        // the literal `__SEO_TITLE__`-style tokens (plus whatever partial
        // head/header HTML rendered so far) would flush to the client
        // alongside the Location header. A redirect has no meaningful body
        // anyway, so drop it instead of leaking it.
        if (ob_get_level() > 0) {
            ob_end_clean();
        }
        header('Location: ' . $url);
        exit;
    }
}
