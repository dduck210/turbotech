<?php

namespace Codemoi\Core;

use RuntimeException;

/**
 * Renders view templates from the project root's `view/` directory.
 *
 * Replaces the old `extract($data); include $view;` pattern used in
 * `admin/controller/controller.php:2-7` and the raw `include "view/...";`
 * calls scattered across `index.php`. The key fix here is scope isolation:
 * `extract()` + `include` both run inside `renderIsolated()`'s own local
 * scope, so the `$data` array's keys never leak into the caller's scope
 * (unlike the old `extract($one_pro)` / `extract($cate)` calls that ran
 * directly in `index.php`'s global scope).
 *
 * Templates keep reading variables by the exact same names they always
 * have (e.g. `$listpro`, `$namecate`, `$listcate`) — only the mechanism
 * delivering those variables changes.
 */
class View
{
    /**
     * Render a template file with the given data.
     *
     * @param string $template Template path relative to `view/`, without
     *                         the `.php` extension, e.g. `'product/product-list'`
     *                         resolves to `view/product/product-list.php`.
     * @param array $data Associative array of variables made available to
     *                    the template under their array keys.
     * @throws RuntimeException if the resolved template file does not exist.
     */
    public static function render(string $template, array $data = []): void
    {
        $path = self::resolvePath($template);

        if (!is_file($path)) {
            throw new RuntimeException("View template not found: {$template} (resolved to {$path})");
        }

        self::renderIsolated($path, $data);
    }

    /**
     * Resolve a template name to an absolute filesystem path under the
     * project root's `view/` directory.
     */
    private static function resolvePath(string $template): string
    {
        $root = dirname(__DIR__, 2);

        return $root . '/view/' . $template . '.php';
    }

    /**
     * Include the template file with `$data` extracted into local scope only.
     * Running `extract()` and `include` inside this dedicated method keeps
     * the extracted variables confined here — they never touch the calling
     * scope (e.g. a controller method or another view).
     */
    private static function renderIsolated(string $__path, array $__data): void
    {
        extract($__data);
        include $__path;
    }
}
