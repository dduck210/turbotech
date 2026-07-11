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
            $full_name = trim($_POST['full_name'] ?? '');
            $user_name = $user['user_name'];
            $password = $user['password'];
            $sex = $_POST['sex'] ?? '';
            $email_user = trim($_POST['email_user'] ?? '');
            $province = trim($_POST['province'] ?? '');
            $ward = trim($_POST['ward'] ?? '');
            $address_detail = trim($_POST['address_detail'] ?? '');
            $phone_user = trim($_POST['phone_user'] ?? '');
            $address = $address_detail !== '' ? "{$address_detail}, {$ward}, {$province}" : '';

            if ($full_name === '' || !filter_var($email_user, FILTER_VALIDATE_EMAIL) || $province === '' || $ward === '' || $address_detail === '') {
                echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:"Vui lòng nhập đầy đủ và đúng định dạng thông tin !",showConfirmButton:false,timer:3000}));</script>';
            } else {
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

                echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"success",title:"Thay đổi thông tin thành công!",showConfirmButton:false,timer:3000}));</script>';
            }
        }

        if (isset($_POST['btn_pass'])) {
            $user_name = $user['user_name'];
            $password = $_POST['newpass'] ?? '';

            if ($password == "") {
                echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:"Không được để trống mật khẩu mới !",showConfirmButton:false,timer:3000}));</script>';
            } elseif (($_POST['repass'] ?? null) !== ($_POST['newpass'] ?? null)) {
                echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:"Nhập lại mật khẩu không khớp !",showConfirmButton:false,timer:3000}));</script>';
            } else {
                User::updatePassword($user_name, $password);
                echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"success",title:"Đổi mật khẩu thành công !",showConfirmButton:false,timer:3000}));</script>';
            }
        }

        $list_mybill = Order::allByUser($user['id_user']);
        $cancelMessage = $_SESSION['cancelMessage'] ?? null;
        unset($_SESSION['cancelMessage']);

        $this->view('user/myaccount', [
            'list_mybill' => $list_mybill,
            'cancelMessage' => $cancelMessage,
        ]);
    }

    /**
     * Cancel an order placed by the currently logged-in user. Only orders
     * still in the "Đơn hàng mới" (0) state can be cancelled — enforced in
     * `Order::cancel()`'s WHERE clause, not just here, so this can't be
     * bypassed by calling the route directly with a crafted `id_bill`.
     */
    public function cancelOrder(): void
    {
        if (!Auth::check()) {
            $this->redirect('?act=login');
        }

        $user = Auth::user();
        $id_bill = $_POST['id_bill'] ?? 0;

        $cancelled = Order::cancel($id_bill, $user['id_user']);
        $_SESSION['cancelMessage'] = $cancelled
            ? 'Đã hủy đơn hàng thành công.'
            : 'Không thể hủy đơn hàng này (đơn không tồn tại hoặc đã được xử lý).';

        // #account-orders so the page reopens on the Orders tab instead of
        // resetting to the dashboard — see the hash-handling script in
        // view/user/myaccount.php.
        $this->redirect('?act=myaccount#account-orders');
    }
}
