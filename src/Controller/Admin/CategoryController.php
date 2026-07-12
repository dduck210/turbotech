<?php

namespace Codemoi\Controller\Admin;

use Codemoi\Model\Category;
use PDOException;

/**
 * Category CRUD. Ported from `public/admin/index.php` cases
 * `add_category`/`list_category`/`edit_category`/`update_category`/`delete_cate`.
 */
class CategoryController extends AdminController
{
    public function add(): void
    {
        $this->requireAdmin();

        if (isset($_POST['btn_add']) && $_POST['btn_add']) {
            $name_cate = $_POST['name_cate'] ?? '';

            if ($name_cate == null) {
                echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:"Vui lòng nhập đầy đủ !",showConfirmButton:false,timer:3000}));</script>';
            } else {
                Category::create($name_cate);
                echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"success",title:"Thêm loại thành công!",showConfirmButton:false,timer:3000}));</script>';
            }
        }

        $this->render('add_category');
    }

    public function list(): void
    {
        $this->requireAdmin();
        $this->render('list_category', ['ds_loai' => Category::all()]);
    }

    public function edit(): void
    {
        $this->requireAdmin();

        $one_loai = null;
        if (isset($_GET['id_cate']) && $_GET['id_cate'] > 0) {
            $one_loai = Category::find((int) $_GET['id_cate']);
        }

        $this->render('update_category', ['one_loai' => $one_loai]);
    }

    /**
     * Old code had no admin-session guard on this action at all (only the
     * global CSRF check applied) — a real authorization gap, since anyone
     * who'd loaded the login page (unauthenticated, but session-holding)
     * could obtain a valid CSRF token and POST here directly. Fixed by
     * adding `requireAdmin()`, consistent with every other admin action.
     */
    public function update(): void
    {
        $this->requireAdmin();

        if (isset($_POST['btn_update']) && $_POST['btn_update']) {
            $id_cate = (int) $_POST['id_cate'];
            $name_cate = trim($_POST['name_cate'] ?? '');

            if ($name_cate === '') {
                echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:"Vui lòng nhập tên loại !",showConfirmButton:false,timer:3000}));</script>';
                $this->render('update_category', ['one_loai' => Category::find($id_cate)]);
                return;
            }

            Category::update($id_cate, $name_cate);
            $_SESSION['flash_success'] = 'Cập nhật loại thành công!';
        }

        $this->redirect('index.php?act=list_category');
    }

    /** Same missing-guard gap as update() above — fixed the same way. */
    public function delete(): void
    {
        $this->requireAdmin();

        if (isset($_GET['id_cate']) && $_GET['id_cate'] > 0) {
            try {
                Category::delete((int) $_GET['id_cate']);
            } catch (PDOException $e) {
                // SQLSTATE 23000: category still referenced by a product
                // (lk_cate_product) — block the delete with a clear
                // message instead of the raw fatal error.
                if ($e->getCode() === '23000') {
                    $_SESSION['flash_error'] = 'Không thể xoá loại sản phẩm này vì vẫn còn sản phẩm thuộc loại này.';
                } else {
                    throw $e;
                }
            }
        }

        $this->redirect('index.php?act=list_category');
    }
}
