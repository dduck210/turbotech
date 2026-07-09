<?php

namespace Codemoi\Core;

/**
 * Minimal `act`-keyed router replacing the `switch ($_GET['act']) { ... }`
 * block in `index.php:36-451` (plus the outer `isset($_GET['act'])` guard
 * at `index.php:36,449`). Routing key stays the `act` query string value
 * so existing URLs keep working unchanged.
 *
 * A handler is either a PHP `callable` or an `[ControllerClass::class, 'method']`
 * pair; in the latter case the controller class is instantiated (fresh
 * instance per dispatch) before the method is invoked.
 */
class Router
{
    /** @var array<string, callable|array{0:class-string,1:string}> */
    private array $routes = [];

    /** @var callable|array{0:class-string,1:string}|null */
    private $default = null;

    /**
     * Register a route handler for an `act` value.
     *
     * @param string $act The `act` query string value, e.g. `'product'`.
     * @param callable|array{0:class-string,1:string} $handler
     */
    public function add(string $act, $handler): void
    {
        $this->routes[$act] = $handler;
    }

    /**
     * Register the fallback handler used when `act` is empty or unknown.
     * Mirrors the old `default:` case and the outer `else` branch that both
     * fell back to `include "view/content.php"` (`index.php:445-451`).
     *
     * @param callable|array{0:class-string,1:string} $handler
     */
    public function setDefault($handler): void
    {
        $this->default = $handler;
    }

    /**
     * Look up the handler for `$act` and invoke it. Falls back to the
     * default handler when `$act` is empty or has no registered route —
     * this never throws for an unknown/empty `act`, matching the old
     * switch's `default` case behavior.
     */
    public function dispatch(string $act): void
    {
        $handler = ($act !== '' && isset($this->routes[$act]))
            ? $this->routes[$act]
            : $this->default;

        if ($handler === null) {
            return;
        }

        $this->invoke($handler);
    }

    /**
     * @param callable|array{0:class-string,1:string} $handler
     */
    private function invoke($handler): void
    {
        if (is_array($handler) && isset($handler[0], $handler[1]) && is_string($handler[0])) {
            [$class, $method] = $handler;
            $controller = new $class();
            $controller->$method();
            return;
        }

        call_user_func($handler);
    }
}
