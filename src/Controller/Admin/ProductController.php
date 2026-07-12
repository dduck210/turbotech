<?php

namespace Codemoi\Controller\Admin;

use Codemoi\Model\Category;
use Codemoi\Model\Product;
use PDOException;

/**
 * Product CRUD + image upload. Ported from `public/admin/index.php`
 * cases `add_product`/`list_product`/`edit_product`/`update_product`/`delete_product`.
 */
class ProductController extends AdminController
{
    private const UPLOAD_DIR = './uploads/';
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif'];

    public function add(): void
    {
        $this->requireAdmin();

        if (isset($_POST['btn_add']) && $_POST['btn_add']) {
            $name_pro = $_POST['name_pro'];
            $price = $_POST['price'];
            $discount = $_POST['discount'];
            $short_des = $_POST['short_des'];
            $detail_des = $_POST['detail_des'];
            $idcate = $_POST['idcate'];
            $stock = $_POST['stock'] ?? 0;
            $stock_message = trim($_POST['stock_message'] ?? '') ?: null;
            $hasFile = !empty($_FILES['img_pro']['tmp_name']);
            $img_pro = $hasFile ? $this->moveUpload($_FILES['img_pro']) : null;

            if ($name_pro == null || $price == null || $short_des == null || $idcate == null) {
                echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:"Vui lòng nhập đầy đủ nội dung !",showConfirmButton:false,timer:3000}));</script>';
            } elseif ($price <= 0) {
                echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:"Giá nhập không đúng !",showConfirmButton:false,timer:3000}));</script>';
            } elseif ($stock < 0) {
                echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:"Số lượng tồn kho không đúng !",showConfirmButton:false,timer:3000}));</script>';
            } elseif ($img_pro === null) {
                echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:"File ảnh không phù hợp !",showConfirmButton:false,timer:3000}));</script>';
            } else {
                Product::create($name_pro, $price, $discount, $img_pro, $short_des, $detail_des, (int) $idcate, (int) $stock, $stock_message);
                echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"success",title:"Thêm sản phẩm thành công !",showConfirmButton:false,timer:3000}));</script>';
            }
        }

        $this->render('add_product', ['ds_loai' => Category::all()]);
    }

    public function list(): void
    {
        $this->requireAdmin();

        $idcate = (isset($_POST['btn_filter']) && $_POST['btn_filter']) ? $_POST['idcate'] : 0;

        $this->render('list_product', [
            'ds_loai' => Category::all(),
            'listpro' => Product::allAdmin((int) $idcate),
            'idcate' => $idcate,
        ]);
    }

    public function edit(): void
    {
        $this->requireAdmin();

        $pro = null;
        if (isset($_GET['id_pro']) && $_GET['id_pro'] > 0) {
            $pro = Product::one((int) $_GET['id_pro']);
        }

        $this->render('update_product', ['ds_loai' => Category::all(), 'pro' => $pro]);
    }

    /** Old code had no admin-session guard here either — see CategoryController::update() for why. */
    public function update(): void
    {
        $this->requireAdmin();

        if (isset($_POST['btn_update']) && $_POST['btn_update'] > 0) {
            $id_pro = (int) $_POST['id_pro'];
            $idcate = (int) $_POST['idcate'];
            $name_pro = $_POST['name_pro'];
            $price = $_POST['price'];
            $discount = $_POST['discount'];
            $short_des = $_POST['short_des'];
            $detail_des = $_POST['detail_des'];
            $stock = (int) ($_POST['stock'] ?? 0);
            $stock_message = trim($_POST['stock_message'] ?? '') ?: null;
            $img_pro = $this->moveUpload($_FILES['img_pro'] ?? null) ?? '';

            Product::update($id_pro, $name_pro, $price, $discount, $short_des, $detail_des, $img_pro, $idcate, $stock, $stock_message);
            $_SESSION['flash_success'] = 'Cập nhật sản phẩm thành công!';
            $this->redirect('index.php?act=list_product');
        }
    }

    /** Same missing-guard gap as elsewhere — fixed via requireAdmin(). */
    public function delete(): void
    {
        $this->requireAdmin();

        if (isset($_GET['id_pro']) && $_GET['id_pro'] > 0) {
            try {
                Product::delete((int) $_GET['id_pro']);
            } catch (PDOException $e) {
                // SQLSTATE 23000: product already referenced by an order
                // line in `cart` (lk_pro_cart) — deleting it would break
                // that order's history, so block it with a clear message
                // instead of the raw fatal error.
                if ($e->getCode() === '23000') {
                    $_SESSION['flash_error'] = 'Không thể xoá sản phẩm này vì đã có đơn hàng liên quan.';
                } else {
                    throw $e;
                }
            }
        }

        $this->redirect('index.php?act=list_product');
    }

    /**
     * Validates and moves an uploaded product image. Returns the sanitized
     * stored filename on success, or null if there's no file, the extension
     * isn't allowed, the filename hides a second extension (e.g.
     * `shell.php.jpg`), or the move itself fails — callers must only write
     * `$img_pro`/skip the column when this returns null, never fall back to
     * the raw `$_FILES[...]['name']`.
     */
    private function moveUpload(?array $file): ?string
    {
        if ($file === null || $file['tmp_name'] === '') {
            return null;
        }

        $name = basename($file['name']);
        $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));

        if (!in_array($extension, self::ALLOWED_EXTENSIONS, true) || preg_match('/\.(php|phtml|pht)/i', $name)) {
            return null;
        }

        if (!move_uploaded_file($file['tmp_name'], self::UPLOAD_DIR . $name)) {
            return null;
        }

        return $name;
    }
}
