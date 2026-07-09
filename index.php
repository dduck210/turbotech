<?php

require __DIR__ . '/vendor/autoload.php';

use Codemoi\Controller\AccountController;
use Codemoi\Controller\AuthController;
use Codemoi\Controller\CartController;
use Codemoi\Controller\CheckoutController;
use Codemoi\Controller\HomeController;
use Codemoi\Controller\PageController;
use Codemoi\Controller\PasswordController;
use Codemoi\Controller\ProductController;
use Codemoi\Controller\QuestionController;
use Codemoi\Core\Router;
use Codemoi\Model\Cart;
use Codemoi\Model\Category;

session_start();
ob_start();

// kiểm tra session my cart đã tồn tại là 1 mảng chưa, nếu chưa thì khởi tạo 1 mảng mới
Cart::items();

include __DIR__ . '/view/head.php';

// load danh mục dùng chung cho header/sidebar
$listcate = Category::all();

include __DIR__ . '/view/header.php';

$router = new Router();
$router->add('product', [ProductController::class, 'index']);
$router->add('prodetail', [ProductController::class, 'detail']);
$router->add('register', [AuthController::class, 'register']);
$router->add('login', [AuthController::class, 'login']);
$router->add('logout', [AuthController::class, 'logout']);
$router->add('mk', [PasswordController::class, 'methods']);
$router->add('usermk', [PasswordController::class, 'byNameEmail']);
$router->add('forgotPass', [PasswordController::class, 'forgot']);
$router->add('verification', [PasswordController::class, 'verification']);
$router->add('changePass', [PasswordController::class, 'change']);
$router->add('myaccount', [AccountController::class, 'index']);
$router->add('viewcart', [CartController::class, 'viewCart']);
$router->add('edit', [CartController::class, 'edit']);
$router->add('addtocart', [CartController::class, 'add']);
$router->add('removecart', [CartController::class, 'remove']);
$router->add('bill', [CheckoutController::class, 'bill']);
$router->add('pay', [CheckoutController::class, 'pay']);
$router->add('billconfirm', [CheckoutController::class, 'confirm']);
$router->add('viewbill', [CheckoutController::class, 'viewbill']);
$router->add('question', [QuestionController::class, 'index']);
$router->add('introduce', [PageController::class, 'introduce']);
$router->add('contact', [PageController::class, 'contact']);
$router->setDefault([HomeController::class, 'index']);

$router->dispatch($_GET['act'] ?? '');

include __DIR__ . '/view/footer.php';
ob_end_flush();
