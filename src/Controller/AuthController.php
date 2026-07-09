<?php

namespace Codemoi\Controller;

use Codemoi\Core\Controller;
use Codemoi\Model\Auth;
use Codemoi\Model\User;

/**
 * Registration/login/logout. Ported from `index.php` cases `'register'`
 * (`index.php:76-87`), `'login'` (`index.php:90-104`) and `'logout'`
 * (`index.php:106-109`).
 */
class AuthController extends Controller
{
    public function register(): void
    {
        if (isset($_POST['btn_register']) && $_POST['btn_register']) {
            $user_name = $_POST['user_name'] ?? '';
            $full_name = $_POST['full_name'] ?? '';
            $email_user = $_POST['email_user'] ?? '';
            $password = $_POST['password'] ?? '';
            $address = $_POST['address'] ?? '';
            $phone_user = $_POST['phone_user'] ?? '';

            User::register($user_name, $full_name, $email_user, $password, $address, $phone_user);
            echo '<script>alert("Đăng ký tài khoản thành công! Vui lòng đăng nhập")</script>';
            // NOTE: old code's redirect target has a pre-existing typo
            // ('act-login' instead of 'act=login', `index.php:84`); the
            // browser discards the buffered body below on redirect either
            // way, so we stop here instead of also re-rendering the form.
            $this->redirect('index.php?act-login');
        }

        $this->view('nguoidung/register');
    }

    public function login(): void
    {
        if (isset($_POST['btn_login']) && $_POST['btn_login']) {
            $user_name = $_POST['user_name'] ?? '';
            $password = $_POST['password'] ?? '';
            $check_user = User::check($user_name, $password);

            if (is_array($check_user)) {
                Auth::login($check_user);
                $this->redirect('index.php');
            }

            echo '<script>alert("Tài khoản sai hoặc không tồn tại!")</script>';
        }

        $this->view('nguoidung/login');
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect('index.php?act=login');
    }
}
