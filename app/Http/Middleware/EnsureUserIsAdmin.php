<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Replaces the legacy `Controller\Admin\AdminController::requireAdmin()`
 * (`isset($_SESSION['admin'])` check). A single `users` table + `role`
 * column means this is just a role check on the already-authenticated
 * user — no separate admin guard/session needed (see the migration plan's
 * Phase 3 rationale).
 */
class EnsureUserIsAdmin
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || (int) $request->user()->role !== 1) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        return $next($request);
    }
}
