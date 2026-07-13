<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../src/Core/helpers.php';

registerErrorHandler();

use Codemoi\Core\Csrf;
use Codemoi\Core\Router;
use Codemoi\Controller\Admin\AuthController;
use Codemoi\Controller\Admin\DashboardController;
use Codemoi\Controller\Admin\CategoryController;
use Codemoi\Controller\Admin\ProductController;
use Codemoi\Controller\Admin\UserController;
use Codemoi\Controller\Admin\CouponController;
use Codemoi\Controller\Admin\BillController;
use Codemoi\Controller\Admin\CommentController;
use Codemoi\Controller\Admin\QuestionController;
use Codemoi\Controller\Admin\StatsController;

// Every admin POST action goes through here — one guard covers the whole
// panel. Failure sets a flash message and bounces back to the referring
// page instead of a white-screen 403 (list_* pages already display
// $_SESSION['flash_error']).
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !Csrf::verify($_POST['_token'] ?? null)) {
    $_SESSION['flash_error'] = 'Phiên làm việc đã hết hạn, vui lòng thử lại.';
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
    exit;
}

// These delete/approve/ship/cancel actions are plain GET links (not forms),
// so the POST-only check above doesn't cover them — without this, an
// <img src="admin/index.php?act=delete_product&..."> on any page an admin
// visits would trigger the action via their session cookie (CSRF-via-GET).
$get_mutating_actions = [
    'delete_cate', 'delete_product', 'delete_usser', 'approve_bill',
    'ship_bill', 'cancel_bill', 'delete_cmt', 'delete_ques', 'delete_coupon',
];
if (
    $_SERVER['REQUEST_METHOD'] === 'GET'
    && in_array($_GET['act'] ?? '', $get_mutating_actions, true)
    && !Csrf::verify($_GET['_token'] ?? null)
) {
    $_SESSION['flash_error'] = 'Phiên làm việc đã hết hạn, vui lòng thử lại.';
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
    exit;
}

// Every admin `act` routes through here onto Codemoi\Controller\Admin\*
// (the old procedural switch this replaced is gone — see plan phases 07-09).
$router = new Router();
$router->add('dashboard', [DashboardController::class, 'index']);
$router->add('login', [AuthController::class, 'login']);
$router->add('logout', [AuthController::class, 'logout']);
$router->add('add_category', [CategoryController::class, 'add']);
$router->add('list_category', [CategoryController::class, 'list']);
$router->add('edit_category', [CategoryController::class, 'edit']);
$router->add('update_category', [CategoryController::class, 'update']);
$router->add('delete_cate', [CategoryController::class, 'delete']);
$router->add('add_product', [ProductController::class, 'add']);
$router->add('list_product', [ProductController::class, 'list']);
$router->add('edit_product', [ProductController::class, 'edit']);
$router->add('update_product', [ProductController::class, 'update']);
$router->add('delete_product', [ProductController::class, 'delete']);
$router->add('list_user', [UserController::class, 'list']);
$router->add('edit_user', [UserController::class, 'edit']);
$router->add('update_user', [UserController::class, 'update']);
$router->add('delete_usser', [UserController::class, 'delete']);
$router->add('list_coupon', [CouponController::class, 'list']);
$router->add('add_coupon', [CouponController::class, 'add']);
$router->add('delete_coupon', [CouponController::class, 'delete']);
$router->add('edit_coupon', [CouponController::class, 'edit']);
$router->add('update_coupon', [CouponController::class, 'update']);
$router->add('list_bill', [BillController::class, 'list']);
$router->add('edit_bill', [BillController::class, 'edit']);
$router->add('update_bill', [BillController::class, 'update']);
$router->add('approve_bill', [BillController::class, 'approve']);
$router->add('ship_bill', [BillController::class, 'ship']);
$router->add('cancel_bill', [BillController::class, 'cancel']);
$router->add('billdetail', [BillController::class, 'detail']);
$router->add('list_cmt', [CommentController::class, 'list']);
$router->add('delete_cmt', [CommentController::class, 'delete']);
$router->add('list_ques', [QuestionController::class, 'list']);
$router->add('delete_ques', [QuestionController::class, 'delete']);
$router->add('list_thongke', [StatsController::class, 'index']);
$router->setDefault([DashboardController::class, 'index']);

$act = $_GET['act'] ?? '';

// Every admin action is now ported (Phase 07-09) — the old procedural
// switch this replaced is gone. Router::dispatch() already falls back to
// DashboardController for an empty/unregistered `act`, matching the old
// switch's `default:`/no-`act` behavior.
$router->dispatch($act === '/' ? '' : $act);

