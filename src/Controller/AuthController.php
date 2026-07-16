<?php

namespace Codemoi\Controller;

use Codemoi\Core\Controller;
use Codemoi\Core\Csrf;
use Codemoi\Core\Seo;
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
        Seo::setTitle('Đăng ký tài khoản - Turbotech');
        Seo::setDescription('Tạo tài khoản Turbotech để mua sắm laptop gaming, PC và theo dõi đơn hàng dễ dàng.');

        if (isset($_POST['btn_register']) && $_POST['btn_register']) {
            $user_name = trim($_POST['user_name'] ?? '');
            $full_name = trim($_POST['full_name'] ?? '');
            $email_user = trim($_POST['email_user'] ?? '');
            $password = $_POST['password'] ?? '';
            $sex = ($_POST['sex'] ?? '0') === '1' ? 1 : 0;
            $province = trim($_POST['province'] ?? '');
            $ward = trim($_POST['ward'] ?? '');
            $address_detail = trim($_POST['address_detail'] ?? '');
            $phone_user = trim($_POST['phone_user'] ?? '');

            $error = $this->validateRegistration($user_name, $full_name, $email_user, $password, $province, $ward, $address_detail, $phone_user);
            $address = $address_detail !== '' ? "{$address_detail}, {$ward}, {$province}" : '';

            // Checked after the format checks (so "invalid phone" still wins
            // over "phone taken") but before insert. Phone/email duplicates
            // get their own inline banner with a "Quên mật khẩu?" link
            // instead of a plain alert(), since that's almost always someone
            // who already has an account and typed the wrong username —
            // an alert can't offer a clickable way out, a rendered link can.
            $duplicateField = $error === null ? User::findDuplicateField($user_name, $email_user, $phone_user) : null;

            if ($error === null && $duplicateField === null) {
                User::register($user_name, $full_name, $email_user, $password, $address, $phone_user, $sex);
                // Flash message survives the redirect (a script echoed right
                // before header('Location: ...') never runs — the browser
                // never executes a 3xx response's body). Also fixes a
                // pre-existing typo in the redirect target itself
                // ('act-login' instead of 'act=login', `index.php:84`),
                // which sent every new signup to the homepage instead of
                // the login page.
                $_SESSION['flash_success'] = 'Đăng ký tài khoản thành công! Vui lòng đăng nhập';
                $this->redirect('index.php?act=login');
            }

            if ($duplicateField === 'username') {
                echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:"Tên đăng nhập này đã được sử dụng, vui lòng chọn tên khác",showConfirmButton:false,timer:3000}));</script>';
            } elseif ($error !== null) {
                echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:' . json_encode($error) . ',showConfirmButton:false,timer:3000}));</script>';
            }

            $this->view('user/register', ['duplicateField' => $duplicateField]);
            return;
        }

        $this->view('user/register');
    }

    /**
     * Server-side mirror of the data-rules checks in view/user/register.php
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

        return null;
    }

    public function login(): void
    {
        Seo::setTitle('Đăng nhập - Turbotech');
        Seo::setDescription('Đăng nhập tài khoản Turbotech để mua sắm laptop gaming, PC chính hãng và theo dõi đơn hàng.');

        if (isset($_POST['btn_login']) && $_POST['btn_login']) {
            $user_name = $_POST['user_name'] ?? '';
            $password = $_POST['password'] ?? '';
            $check_user = User::check($user_name, $password);

            if (is_array($check_user)) {
                Auth::login($check_user);
                Csrf::rotate();
                $_SESSION['flash_success'] = 'Đăng nhập thành công!';

                // An admin account (role=1) logging in through the client-facing
                // form goes straight to the admin panel instead of the storefront
                // homepage. Admin sessions are tracked separately from client ones
                // ($_SESSION['admin'] vs $_SESSION['user'] — see
                // Controller\Admin\AdminController::requireAdmin()), so both must
                // be set here or the admin panel would immediately bounce them
                // back to its own login screen despite already being authenticated.
                if ((int) $check_user['role'] === 1) {
                    $_SESSION['admin'] = $check_user;
                    $this->redirect('admin/index.php');
                }

                $this->redirect('index.php');
            }

            echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:"Tài khoản sai hoặc không tồn tại!",showConfirmButton:false,timer:3000}));</script>';
        }

        $this->view('user/login');
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect('index.php?act=login');
    }
}
