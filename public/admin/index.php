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
include __DIR__ . '/../../admin/model/category.php';
include __DIR__ . '/../../admin/model/product.php';
include __DIR__ . '/../../admin/model/user.php';
include __DIR__ . '/../../admin/model/bill.php';
include __DIR__ . '/../../admin/model/comment.php';
include __DIR__ . '/../../admin/model/statistics.php';
include __DIR__ . '/../../admin/model/question.php';
include __DIR__ . '/../../admin/model/coupon.php';
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
$router->setDefault([DashboardController::class, 'index']);

$act = $_GET['act'] ?? '';
$portedActs = [
    'dashboard', '/', 'login', 'logout', '',
    'add_category', 'list_category', 'edit_category', 'update_category', 'delete_cate',
    'add_product', 'list_product', 'edit_product', 'update_product', 'delete_product',
    'list_user', 'edit_user', 'update_user', 'delete_usser',
];

if (in_array($act, $portedActs, true)) {
    $router->dispatch($act === '/' ? '' : $act);
} else {
    switch ($act) {
        //CONTROLLER HÓA ĐƠN

        // show all bill
        case 'list_bill':
            if (isset($_SESSION['admin'])) {
                $status = -1;
                $keyword = "";
                $from_date = "";
                $to_date = "";
                if (isset($_POST['btn_filter'])) {
                    if (isset($_POST['status'])) {
                        $status = (int)$_POST['status'];
                    }
                    if (isset($_POST['keyword'])) {
                        $keyword = trim($_POST['keyword']);
                    }
                    if (isset($_POST['from_date'])) {
                        $from_date = $_POST['from_date'];
                    }
                    if (isset($_POST['to_date'])) {
                        $to_date = $_POST['to_date'];
                    }
                }
                $listbill = loadall_bill(0, $status, $keyword, $from_date, $to_date);
                $flash_error = $_SESSION['flash_error'] ?? null;
                $flash_success = $_SESSION['flash_success'] ?? null;
                unset($_SESSION['flash_error'], $_SESSION['flash_success']);
                render('list_bill', [
                    'listbill' => $listbill,
                    'status' => $status,
                    'keyword' => $keyword,
                    'from_date' => $from_date,
                    'to_date' => $to_date,
                    'flash_error' => $flash_error,
                    'flash_success' => $flash_success
                ]);
            } else {
                header("location: index.php?act=login");
            }
            break;
        //     xóa bill: 
        // case 'removebill':
        //     if (isset($_GET['idbill']) && ($_GET['idbill'])) {
        //         $idbill = $_GET['idbill'];
        //         remove_bill($idbill);
        //     }
        //     $listbill = loadall_bill(0);
        //     include "view/hoadon/list.php";
        //     break;
        case 'edit_bill':
            if (isset($_SESSION['admin'])) {
                $one_bill = (isset($_GET['idbill']) && ($_GET['idbill']) > 0)
                    ? loadone_bill($_GET['idbill'])
                    : false;

                if (!is_array($one_bill)) {
                    $_SESSION['flash_error'] = 'Không tìm thấy đơn hàng này.';
                    header('location:index.php?act=list_bill');
                    break;
                }

                render(
                    'update_bill',
                    ['one_bill' => $one_bill]
                );
            } else {
                header("location: index.php?act=login");
            }

            break;
        case 'update_bill':
            if (isset($_POST['btn_update']) && ($_POST['btn_update'])) {
                $id_bill = $_POST['id_bill'];
                $status = $_POST['status'];
                $status_pay = $_POST['status_pay'];
                if ($status == 3) {
                    $status_pay = 1;
                }
                update_bill($id_bill, $status, $status_pay);
                $_SESSION['flash_success'] = 'Cập nhật đơn hàng thành công!';
                header('location:index.php?act=list_bill');
            }
            break;
        case 'approve_bill':
            if (isset($_GET['idbill']) && ($_GET['idbill'] > 0)) {
                $idbill = $_GET['idbill'];
                $bill = loadone_bill($idbill);
                if ($bill && $bill['status'] == 0) {
                    update_bill($idbill, '1', $bill['status_pay']);
                    $_SESSION['flash_success'] = 'Đã duyệt đơn hàng thành công!';
                }
            }
            header('location:index.php?act=list_bill');
            break;
        case 'ship_bill':
            if (isset($_GET['idbill']) && ($_GET['idbill'] > 0)) {
                $idbill = $_GET['idbill'];
                $bill = loadone_bill($idbill);
                if ($bill && $bill['status'] == 1) {
                    update_bill($idbill, '2', $bill['status_pay']);
                    $_SESSION['flash_success'] = 'Đã chuyển sang đang giao hàng!';
                }
            }
            header('location:index.php?act=list_bill');
            break;
        case 'cancel_bill':
            if (isset($_GET['idbill']) && ($_GET['idbill'] > 0)) {
                $idbill = $_GET['idbill'];
                $bill = loadone_bill($idbill);
                if ($bill && ($bill['status'] == 0 || $bill['status'] == 1)) {
                    update_bill($idbill, '4', $bill['status_pay']);
                    $_SESSION['flash_success'] = 'Đã hủy đơn hàng!';
                }
            }
            header('location:index.php?act=list_bill');
            break;
        case 'billdetail':
            if (isset($_SESSION['admin'])) {
                $one_bill = (isset($_GET['idbill']) && ($_GET['idbill']) > 0)
                    ? loadone_bill($_GET['idbill'])
                    : false;

                if (!is_array($one_bill)) {
                    $_SESSION['flash_error'] = 'Không tìm thấy đơn hàng này.';
                    header('location:index.php?act=list_bill');
                    break;
                }

                render(
                    'billdetail',
                    ['one_bill' => $one_bill]
                );
            } else {
                header("location: index.php?act=login");
            }

            break;
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

        case 'list_coupon':
            if (isset($_SESSION['admin'])) {
                $listcoupon = loadall_coupon();
                $flash_success = $_SESSION['flash_success'] ?? null;
                unset($_SESSION['flash_success']);
                render('list_coupon', ['listcoupon' => $listcoupon, 'flash_success' => $flash_success]);
            } else {
                header("location: index.php?act=login");
            }
            break;

        case 'add_coupon':
            if (isset($_SESSION['admin'])) {
                if (isset($_POST['btn_add'])) {
                    $code = $_POST['code'];
                    $discount_type = $_POST['discount_type'];
                    $discount_value = $_POST['discount_value'];
                    $max_discount = $_POST['max_discount'];
                    $min_order_value = $_POST['min_order_value'];
                    $product_id = $_POST['product_id'];
                    $start_date = $_POST['start_date'];
                    $end_date = $_POST['end_date'];
                    $usage_limit = $_POST['usage_limit'];
                    $status = $_POST['status'];
                    insert_coupon($code, $discount_type, $discount_value, $max_discount, $min_order_value, $product_id, $start_date, $end_date, $usage_limit, $status);
                    echo '<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"success",title:"Thêm mã giảm giá thành công!",showConfirmButton:false,timer:3000}));</script>';
                }
                $listpro = loadall_pro(); // To select product to apply
                render('add_coupon', ['listpro' => $listpro]);
            } else {
                header("location: index.php?act=login");
            }
            break;

        case 'delete_coupon':
            if (isset($_GET['id_coupon']) && ($_GET['id_coupon']) > 0) {
                $id_coupon = $_GET['id_coupon'];
                delete_coupon($id_coupon);
                $_SESSION['flash_success'] = 'Xóa mã thành công!';
            }
            header('location: index.php?act=list_coupon');
            break;

        case 'edit_coupon':
            if (isset($_SESSION['admin'])) {
                if (isset($_GET['id_coupon']) && ($_GET['id_coupon']) > 0) {
                    $id_coupon = $_GET['id_coupon'];
                    $one_coupon = loadone_coupon($id_coupon);
                }
                $listpro = loadall_pro();
                render('update_coupon', ['one_coupon' => $one_coupon, 'listpro' => $listpro]);
            } else {
                header("location: index.php?act=login");
            }
            break;

        case 'update_coupon':
            if (isset($_POST['btn_update'])) {
                $id_coupon = $_POST['id_coupon'];
                $code = $_POST['code'];
                $discount_type = $_POST['discount_type'];
                $discount_value = $_POST['discount_value'];
                $max_discount = $_POST['max_discount'];
                $min_order_value = $_POST['min_order_value'];
                $product_id = $_POST['product_id'];
                $start_date = $_POST['start_date'];
                $end_date = $_POST['end_date'];
                $usage_limit = $_POST['usage_limit'];
                $status = $_POST['status'];
                update_coupon($id_coupon, $code, $discount_type, $discount_value, $max_discount, $min_order_value, $product_id, $start_date, $end_date, $usage_limit, $status);
                $_SESSION['flash_success'] = 'Cập nhật mã giảm giá thành công!';
            }
            header('location: index.php?act=list_coupon');
            break;

        default:
            // Unreachable in practice: $portedActs above already routes
            // every unregistered/empty `act` to DashboardController via
            // Router::dispatch()'s default handler before this switch runs.
            $router->dispatch($act);
    }
}

