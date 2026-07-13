<?php

namespace Codemoi\Controller\Admin;

use Codemoi\Core\Csrf;
use Codemoi\Model\User;

/**
 * Admin login/logout. Ported from `public/admin/index.php` cases
 * `'login'`/`'logout'`.
 */
class AuthController extends AdminController
{
    public function login(): void
    {
        if (isset($_SESSION['admin'])) {
            $this->redirect('index.php');
        }

        if (isset($_POST['btn_login']) && $_POST['btn_login']) {
            $user_name = $_POST['user_name'] ?? '';
            $password = $_POST['password'] ?? '';

            if ($user_name === '' || $password === '') {
                $_SESSION['flash_error'] = 'Điền đầy đủ thông tin !';
            } else {
                $admin = User::checkAdmin($user_name, $password);

                if (is_array($admin)) {
                    // Regenerate the session ID so a session fixed before
                    // login can't inherit admin access once this succeeds.
                    session_regenerate_id(true);
                    $_SESSION['admin'] = $admin;
                    Csrf::rotate();
                    $_SESSION['flash_success'] = 'Đăng nhập thành công!';
                    $this->redirect('index.php');
                }

                $_SESSION['flash_error'] = 'Tài khoản sai hoặc không tồn tại!';
            }
        }

        $this->render('login');
    }

    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }

        session_destroy();
        $this->redirect('index.php?act=login');
    }
}
