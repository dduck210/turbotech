<?php

namespace Codemoi\Controller\Admin;

use Codemoi\Model\User;

/**
 * User CRUD. Ported from `public/admin/index.php` cases
 * `list_user`/`edit_user`/`update_user`/`delete_usser` — the deliberate
 * typo in the delete act ("usser") is preserved exactly, since
 * `admin/view/list_user.php` already links to that literal URL.
 */
class UserController extends AdminController
{
    public function list(): void
    {
        $this->requireAdmin();
        $this->render('list_user', ['listuser' => User::allAdmin()]);
    }

    public function edit(): void
    {
        $this->requireAdmin();

        $user = null;
        if (isset($_GET['id_user']) && $_GET['id_user'] > 0) {
            $user = User::find((int) $_GET['id_user']);
        }

        $this->render('update_user', ['user' => $user]);
    }

    /** Old code had no admin-session guard here either — see CategoryController::update() for why. */
    public function update(): void
    {
        $this->requireAdmin();

        if (isset($_POST['btn_update']) && $_POST['btn_update']) {
            $id_user = (int) $_POST['id_user'];
            $user_name = trim($_POST['user_name']);
            $full_name = trim($_POST['full_name']);
            $email_user = trim($_POST['email_user']);
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'];

            if ($user_name === '' || $full_name === '') {
                echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:"Vui lòng nhập đầy đủ nội dung !",showConfirmButton:false,timer:3000}));</script>';
                $this->render('update_user', ['user' => User::find($id_user)]);
                return;
            }

            if (!filter_var($email_user, FILTER_VALIDATE_EMAIL)) {
                echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:"Email không hợp lệ !",showConfirmButton:false,timer:3000}));</script>';
                $this->render('update_user', ['user' => User::find($id_user)]);
                return;
            }

            // Blank password is allowed (means "keep current password");
            // only enforce the minimum length when actually changing it.
            if ($password !== '' && strlen($password) < 6) {
                echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:"Mật khẩu phải có ít nhất 6 ký tự !",showConfirmButton:false,timer:3000}));</script>';
                $this->render('update_user', ['user' => User::find($id_user)]);
                return;
            }

            User::updateAdmin($id_user, $user_name, $full_name, $email_user, $password === '' ? null : $password, $role);
            $_SESSION['flash_success'] = 'Cập nhật tài khoản thành công!';
        }

        $this->redirect('index.php?act=list_user');
    }

    /** Same missing-guard gap as elsewhere — fixed via requireAdmin(). No FK references `user` (Phase 06 audit), so no try/catch needed. */
    public function delete(): void
    {
        $this->requireAdmin();

        if (isset($_GET['id_user']) && $_GET['id_user'] > 0) {
            User::deleteAdmin((int) $_GET['id_user']);
        }

        $this->redirect('index.php?act=list_user');
    }
}
