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
            $user_name = trim($_POST['user_name'] ?? '');
            $full_name = trim($_POST['full_name'] ?? '');
            $email_user = trim($_POST['email_user'] ?? '');
            $password = $_POST['password'] ?? '';
            $province = trim($_POST['province'] ?? '');
            $ward = trim($_POST['ward'] ?? '');
            $address_detail = trim($_POST['address_detail'] ?? '');
            $phone_user = trim($_POST['phone_user'] ?? '');

            $error = $this->validateRegistration($user_name, $full_name, $email_user, $password, $province, $ward, $address_detail, $phone_user);
            $address = $address_detail !== '' ? "{$address_detail}, {$ward}, {$province}" : '';

            if ($error === null) {
                User::register($user_name, $full_name, $email_user, $password, $address, $phone_user);
                echo '<script>alert("Đăng ký tài khoản thành công! Vui lòng đăng nhập")</script>';
                // NOTE: old code's redirect target has a pre-existing typo
                // ('act-login' instead of 'act=login', `index.php:84`); the
                // browser discards the buffered body below on redirect either
                // way, so we stop here instead of also re-rendering the form.
                $this->redirect('index.php?act-login');
            }

            echo '<script>alert(' . json_encode($error) . ')</script>';
        }

        $this->view('nguoidung/register');
    }

    /**
     * Server-side mirror of the data-rules checks in view/nguoidung/register.php
     * (src/js/form-validate.js) — a request that skips or tampers with the
     * client-side JS must still be rejected here.
     *
     * @return string|null The first validation error message, or null if valid.
     */
    private function validateRegistration(
        string $user_name,
        string $full_name,
        string $email_user,
        string $password,
        string $province,
        string $ward,
        string $address_detail,
        string $phone_user
    ): ?string {
        if ($user_name === '' || strlen($user_name) < 3) {
            return 'Tên đăng nhập phải có ít nhất 3 ký tự';
        }
        if ($full_name === '' || strlen($full_name) < 2) {
            return 'Vui lòng nhập họ tên hợp lệ';
        }
        if ($email_user === '' || !filter_var($email_user, FILTER_VALIDATE_EMAIL)) {
            return 'Địa chỉ email không hợp lệ';
        }
        if (strlen($password) < 6) {
            return 'Mật khẩu phải có ít nhất 6 ký tự';
        }
        if ($province === '') {
            return 'Vui lòng chọn tỉnh/thành phố';
        }
        if ($ward === '') {
            return 'Vui lòng chọn xã/phường';
        }
        if ($address_detail === '' || strlen($address_detail) < 3) {
            return 'Vui lòng nhập địa chỉ chi tiết hợp lệ';
        }
        if (!preg_match('/^(\+?84|0)\d{9,10}$/', $phone_user)) {
            return 'Số điện thoại không hợp lệ';
        }
        if (User::existsByUsernameOrEmail($user_name, $email_user)) {
            return 'Tên đăng nhập hoặc email đã được sử dụng';
        }

        return null;
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
