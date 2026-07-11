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
     * @param string $template Template path relative to `view/`, e.g. `'product/product-list'`.
     * @param array $data Variables to expose to the template.
     */
    protected function view(string $template, array $data = []): void
    {
        View::render($template, $data);
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
        header('Location: ' . $url);
        exit;
    }
}
