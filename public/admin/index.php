<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../admin/controller/controller.php';

use Codemoi\Core\Csrf;
use Codemoi\Core\Router;
use Codemoi\Controller\Admin\AuthController;
use Codemoi\Controller\Admin\DashboardController;
use Codemoi\Controller\Admin\CategoryController;
use Codemoi\Controller\Admin\ProductController;
use Codemoi\Controller\Admin\UserController;
use Codemoi\Controller\Admin\CouponController;
use Codemoi\Controller\Admin\BillController;

// Every admin POST action goes through here — one guard covers the whole
// panel. Failure sets a flash message and bounces back to the referring
// page instead of a white-screen 403 (list_* pages already display
// $_SESSION['flash_error']).
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !Csrf::verify($_POST['_token'] ?? null)) {
    $_SESSION['flash_error'] = 'Phiên làm việc đã hết hạn, vui lòng thử lại.';
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
    exit;
}

//include dao để dùng các functione:

include __DIR__ . '/../../admin/model/pdo.php';
include __DIR__ . '/../../admin/model/bill.php';
include __DIR__ . '/../../admin/model/comment.php';
include __DIR__ . '/../../admin/model/statistics.php';
include __DIR__ . '/../../admin/model/question.php';
// controller
// Strangler routing: 'dashboard'/'login'/'logout' (plus the empty/'/'
// no-act fallback, which always rendered the dashboard too) are ported
// onto the Codemoi\Controller\Admin\* MVC scaffold. Every other `act`
// still falls through to the original switch below until Phase 08/09
// port each domain onto the same scaffold.
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
$router->setDefault([DashboardController::class, 'index']);

$act = $_GET['act'] ?? '';
$portedActs = [
    'dashboard', '/', 'login', 'logout', '',
    'add_category', 'list_category', 'edit_category', 'update_category', 'delete_cate',
    'add_product', 'list_product', 'edit_product', 'update_product', 'delete_product',
    'list_user', 'edit_user', 'update_user', 'delete_usser',
    'list_coupon', 'add_coupon', 'delete_coupon', 'edit_coupon', 'update_coupon',
    'list_bill', 'edit_bill', 'update_bill', 'approve_bill', 'ship_bill', 'cancel_bill', 'billdetail',
];

if (in_array($act, $portedActs, true)) {
    $router->dispatch($act === '/' ? '' : $act);
} else {
    switch ($act) {
        //CONTROLLER BÌNH LUẬN
        //show list: 
        case 'list_cmt':
            if (isset($_SESSION['admin'])) {
                $listcmt = loadall_cmt();
                render(
                    'list_comment',
                    ['listcmt' => $listcmt]
                );
            } else {
                header("location: index.php?act=login");
            }

            break;
        //xóa bì-nh luận: 
        case 'delete_cmt':
            if (isset($_GET['idcmt']) && ($_GET['idcmt']) > 0) {
                $id_cmt = $_GET['idcmt'];
                remove_cmt($id_cmt);
            }
            header('location: index.php?act=list_cmt');
            break;

        //CONTROLLER THỐNG KÊ
        // Danh sách thống kê
        case 'list_thongke':
            if (isset($_SESSION['admin'])) {
                $from_date = isset($_POST['from_date']) ? $_POST['from_date'] : '';
                $to_date = isset($_POST['to_date']) ? $_POST['to_date'] : '';
                $sort_product = isset($_POST['sort_product']) ? $_POST['sort_product'] : 'DESC';
                
                $revenue_stats = get_revenue_by_date($from_date, $to_date);
                $product_sold_stats = get_products_sold_by_date($from_date, $to_date, $sort_product);
                $inventory_stats = get_inventory();
                
                render('list_statistic', [
                    'revenue_stats' => $revenue_stats, 
                    'product_sold_stats' => $product_sold_stats,
                    'inventory_stats' => $inventory_stats,
                    'from_date' => $from_date,
                    'to_date' => $to_date,
                    'sort_product' => $sort_product
                ]);
            } else {
                header("location: index.php?act=login");
            }
            break;
        // Danh sách hỏi đáp
        case 'list_ques':
            if (isset($_SESSION['admin'])) {
                $listques = question();
                render(
                    'list_question',
                    ['listques' => $listques]
                );
            } else {
                header("location: index.php?act=login");
            }
            break;
        //xóa hỏi đáp: 
        case 'delete_ques':
            if (isset($_GET['id_ques']) && ($_GET['id_ques']) > 0) {
                $id_ques = $_GET['id_ques'];
                delete_ques($id_ques);
            }
            header('location: index.php?act=list_ques');
            break;


        default:
            // Unreachable in practice: $portedActs above already routes
            // every unregistered/empty `act` to DashboardController via
            // Router::dispatch()'s default handler before this switch runs.
            $router->dispatch($act);
    }
}

