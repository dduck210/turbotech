<?php

require __DIR__ . '/../vendor/autoload.php';

use Codemoi\Controller\AccountController;
use Codemoi\Controller\AuthController;
use Codemoi\Controller\CartController;
use Codemoi\Controller\CheckoutController;
use Codemoi\Controller\HomeController;
use Codemoi\Controller\PageController;
use Codemoi\Controller\PasswordController;
use Codemoi\Controller\ProductController;
use Codemoi\Controller\QuestionController;
use Codemoi\Core\Csrf;
use Codemoi\Core\Router;
use Codemoi\Model\Cart;
use Codemoi\Model\Category;

session_start();
ob_start();

// Every state-changing request goes through here, so this is the one place
// a CSRF check covers the whole app. Runs before head.php/header.php so a
// failure's flash message is set in time for header.php's flash display to
// pick it up on the page it redirects back to.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !Csrf::verify($_POST['_token'] ?? null)) {
    $_SESSION['flash_error'] = 'Phiên làm việc đã hết hạn, vui lòng thử lại.';
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
    exit;
}

// kiểm tra session my cart đã tồn tại là 1 mảng chưa, nếu chưa thì khởi tạo 1 mảng mới
Cart::items();

include __DIR__ . '/../view/head.php';

// load danh mục dùng chung cho header/sidebar
$listcate = Category::all();

include __DIR__ . '/../view/header.php';

$router = new Router();
$router->add('product', [ProductController::class, 'index']);
$router->add('prodetail', [ProductController::class, 'detail']);
$router->add('register', [AuthController::class, 'register']);
$router->add('login', [AuthController::class, 'login']);
$router->add('logout', [AuthController::class, 'logout']);
$router->add('mk', [PasswordController::class, 'forgotPassword']);
$router->add('verification', [PasswordController::class, 'verification']);
$router->add('changePass', [PasswordController::class, 'change']);
$router->add('myaccount', [AccountController::class, 'index']);
$router->add('cancelorder', [AccountController::class, 'cancelOrder']);
$router->add('viewcart', [CartController::class, 'viewCart']);
$router->add('edit', [CartController::class, 'edit']);
$router->add('addtocart', [CartController::class, 'add']);
$router->add('removecart', [CartController::class, 'remove']);
$router->add('bill', [CheckoutController::class, 'bill']);
$router->add('applycoupon', [CheckoutController::class, 'applyCoupon']);
$router->add('removecoupon', [CheckoutController::class, 'removeCoupon']);
$router->add('billconfirm', [CheckoutController::class, 'confirm']);
$router->add('viewbill', [CheckoutController::class, 'viewbill']);
$router->add('question', [QuestionController::class, 'index']);
$router->add('introduce', [PageController::class, 'introduce']);
$router->add('contact', [PageController::class, 'contact']);
$router->setDefault([HomeController::class, 'index']);

$router->dispatch($_GET['act'] ?? '');

include __DIR__ . '/../view/footer.php';
ob_end_flush();
