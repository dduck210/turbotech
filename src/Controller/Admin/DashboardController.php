<?php

namespace Codemoi\Controller\Admin;

/**
 * Admin dashboard. Ported from `public/admin/index.php` cases
 * `'/'`/`'dashboard'` and the default/no-`act` fallback (all three
 * rendered the same dashboard).
 *
 * The view itself gathers its stats directly via `Model\*` calls
 * (`\Codemoi\Model\User::allAdmin()`, `\Codemoi\Model\Comment::allAdmin()`,
 * `\Codemoi\Model\Stats::*`, etc. — see admin/view/dashboard.php).
 */
class DashboardController extends AdminController
{
    public function index(): void
    {
        $this->requireAdmin();
        $this->render('dashboard');
    }
}
