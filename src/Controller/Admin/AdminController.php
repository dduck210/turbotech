<?php

namespace Codemoi\Controller\Admin;

use Codemoi\Core\Controller;

/**
 * Base for all admin controllers. Centralizes the role='1' auth guard and
 * the flash-message read/expose that used to be copy-pasted per switch
 * case in `public/admin/index.php` (e.g. `:80`, `:185`, `:261`, `:351`,
 * `:535`).
 *
 * CSRF verification is NOT duplicated here — it already runs at the
 * front-controller level (`public/admin/index.php`, right after
 * `session_start()`) for every POST request, ported or not, so it must
 * stay there rather than move onto a base class only some actions extend.
 */
abstract class AdminController extends Controller
{
    /** Redirect to the admin login screen unless a session is active. */
    protected function requireAdmin(): void
    {
        if (!isset($_SESSION['admin'])) {
            $this->redirect('index.php?act=login');
        }
    }

    /**
     * Render an admin view, auto-injecting the flash messages every admin
     * view already knows how to display (`$flash_success`/`$flash_error`).
     */
    protected function render(string $template, array $data = []): void
    {
        $data['flash_success'] = $this->takeFlash('flash_success');
        $data['flash_error'] = $this->takeFlash('flash_error');

        $this->view($template, $data, 'admin/view');
    }

    private function takeFlash(string $key): ?string
    {
        $value = $_SESSION[$key] ?? null;
        unset($_SESSION[$key]);

        return $value;
    }
}
