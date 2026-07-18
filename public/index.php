<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

/**
 * This project has no dedicated Apache vhost (XAMPP's DocumentRoot is the
 * whole shared htdocs/), so the project-root .htaccess reaches this file
 * via an internal rewrite (e.g. /codemoi1/products -> public/products,
 * falling through to public/index.php). That rewrite leaves SCRIPT_NAME
 * as ".../public/index.php" while REQUEST_URI stays the original
 * ".../codemoi1/products" — the two no longer share a prefix, and
 * Symfony's request base-path detection silently mismatches, 404-ing
 * every route. Stripping the "/public" segment SCRIPT_NAME picked up
 * from the rewrite (but that the browser-visible URL never had) restores
 * the agreement Symfony expects.
 */
if (isset($_SERVER['SCRIPT_NAME']) && str_ends_with($_SERVER['SCRIPT_NAME'], '/public/index.php')) {
    $_SERVER['SCRIPT_NAME'] = substr($_SERVER['SCRIPT_NAME'], 0, -strlen('/public/index.php')).'/index.php';
    $_SERVER['PHP_SELF'] = $_SERVER['SCRIPT_NAME'];
}

(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());
