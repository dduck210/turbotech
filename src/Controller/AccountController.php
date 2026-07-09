<?php

namespace Codemoi\Controller;

use Codemoi\Core\Controller;
use Codemoi\Model\Auth;
use Codemoi\Model\Order;
use Codemoi\Model\User;

/**
 * Account settings page. Ported from `index.php` case `'myaccount'`
 * (`index.php:178-215`).
 *
 * The old code sent a redirect header when logged out but never called
 * `exit`, then continued to build `$list_mybill` from
 * `$_SESSION['user']['id_user']` (undefined for a guest) and still rendered
 * the account page — a latent auth-bypass-adjacent bug. This phase's task
 * explicitly calls for converting bare `header('Location: ...')` calls
 * without `exit` into `$this->redirect()`; doing so here also fixes that
 * bug as a side effect (guests now get a clean redirect, matching the
 * "myaccount (not logged in — should redirect, don't error)" requirement).
 */
class AccountController extends Controller
{
    public function index(): void
    {
        if (!Auth::check()) {
            $this->redirect('?act=login');
        }

        $user = Auth::user();

        if (isset($_POST['btn_change']) && $_POST['btn_change']) {
            $id_user = $_POST['id_user'] ?? $user['id_user'];
            $full_name = $_POST['full_name'] ?? '';
            $user_name = $user['user_name'];
            $password = $user['password'];
            $sex = $_POST['sex'] ?? '';
            $email_user = $_POST['email_user'] ?? '';
            $address = $_POST['address'] ?? '';
            $phone_user = $_POST['phone_user'] ?? '';

            $img_user = $_FILES['img_user']['name'] ?? '';
            if (!empty($_FILES['img_user']['tmp_name'])) {
                $target_file = 'uploads/' . basename($_FILES['img_user']['name']);
                move_uploaded_file($_FILES['img_user']['tmp_name'], $target_file);
            }

            User::update($id_user, $img_user, $full_name, $sex, $email_user, $address, $phone_user);

            $refreshed = User::check($user_name, $password);
            if (is_array($refreshed)) {
                Auth::login($refreshed);
                $user = $refreshed;
            }

            echo '<script>alert("Thay đổi thông tin thành công!")</script>';
        }

        if (isset($_POST['btn_pass'])) {
            $user_name = $user['user_name'];
            $password = $_POST['newpass'] ?? '';

            if ($password == "") {
                echo '<script>alert("Không được để trống mật khẩu mới !")</script>';
            } elseif (($_POST['repass'] ?? null) !== ($_POST['newpass'] ?? null)) {
                echo '<script>alert("Nhập lại mật khẩu không khớp !")</script>';
            } else {
                User::updatePassword($user_name, $password);
                echo '<script>alert("Đổi mật khẩu thành công !")</script>';
            }
        }

        $list_mybill = Order::allByUser($user['id_user']);

        $this->view('nguoidung/myaccount', ['list_mybill' => $list_mybill]);
    }
}
