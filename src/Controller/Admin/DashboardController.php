<?php

namespace Codemoi\Controller\Admin;

/**
 * Admin dashboard. Ported from `public/admin/index.php` cases
 * `'/'`/`'dashboard'` and the default/no-`act` fallback (all three
 * rendered the same dashboard).
 *
 * The view itself still gathers its stats by calling the global
 * `admin/model/*.php` procedural functions directly (`loadall_user()`,
 * `loadall_cmt()`, etc.) — those are shared with the admin actions not yet
 * ported onto this scaffold (Phase 08/09), so porting them onto `Model\*`
 * here would touch files outside this phase's scope.
 */
class DashboardController extends AdminController
{
    public function index(): void
    {
        $this->requireAdmin();
        $this->render('dashboard');
    }
}
