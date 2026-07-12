<?php

namespace Codemoi\Controller\Admin;

use Codemoi\Model\Coupon;
use Codemoi\Model\Product;

/**
 * Coupon CRUD. Ported from `public/admin/index.php` cases
 * `list_coupon`/`add_coupon`/`delete_coupon`/`edit_coupon`/`update_coupon`.
 */
class CouponController extends AdminController
{
    public function list(): void
    {
        $this->requireAdmin();
        $this->render('list_coupon', ['listcoupon' => Coupon::allAdmin()]);
    }

    public function add(): void
    {
        $this->requireAdmin();

        if (isset($_POST['btn_add'])) {
            Coupon::create(
                $_POST['code'],
                (int) $_POST['discount_type'],
                (float) $_POST['discount_value'],
                (float) $_POST['max_discount'],
                (float) $_POST['min_order_value'],
                (int) $_POST['product_id'],
                $_POST['start_date'],
                $_POST['end_date'],
                (int) $_POST['usage_limit'],
                (int) $_POST['status']
            );
            echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"success",title:"Thêm mã giảm giá thành công!",showConfirmButton:false,timer:3000}));</script>';
        }

        $this->render('add_coupon', ['listpro' => Product::allAdmin()]);
    }

    /** Old code had no admin-session guard here — see CategoryController::update() for why. No FK references `coupons` (Phase 06 audit), so no try/catch needed. */
    public function delete(): void
    {
        $this->requireAdmin();

        if (isset($_GET['id_coupon']) && $_GET['id_coupon'] > 0) {
            Coupon::delete((int) $_GET['id_coupon']);
            $_SESSION['flash_success'] = 'Xóa mã thành công!';
        }

        $this->redirect('index.php?act=list_coupon');
    }

    public function edit(): void
    {
        $this->requireAdmin();

        $one_coupon = null;
        if (isset($_GET['id_coupon']) && $_GET['id_coupon'] > 0) {
            $one_coupon = Coupon::find((int) $_GET['id_coupon']);
        }

        $this->render('update_coupon', ['one_coupon' => $one_coupon, 'listpro' => Product::allAdmin()]);
    }

    /** Same missing-guard gap as elsewhere — fixed via requireAdmin(). */
    public function update(): void
    {
        $this->requireAdmin();

        if (isset($_POST['btn_update'])) {
            Coupon::update(
                (int) $_POST['id_coupon'],
                $_POST['code'],
                (int) $_POST['discount_type'],
                (float) $_POST['discount_value'],
                (float) $_POST['max_discount'],
                (float) $_POST['min_order_value'],
                (int) $_POST['product_id'],
                $_POST['start_date'],
                $_POST['end_date'],
                (int) $_POST['usage_limit'],
                (int) $_POST['status']
            );
            $_SESSION['flash_success'] = 'Cập nhật mã giảm giá thành công!';
        }

        $this->redirect('index.php?act=list_coupon');
    }
}
