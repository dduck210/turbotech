<?php

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Core/helpers.php';

registerErrorHandler();

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
use Codemoi\Core\Seo;
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

// `removecart` is a plain GET link (not a form), so the POST-only check
// above doesn't cover it — without this, an <img src="index.php?act=
// removecart"> on any page a logged-in visitor loads would empty their
// cart via their own session cookie (CSRF-via-GET), mirroring the same
// class of gap already closed on the admin side.
if (
    $_SERVER['REQUEST_METHOD'] === 'GET'
    && ($_GET['act'] ?? '') === 'removecart'
    && !Csrf::verify($_GET['_token'] ?? null)
) {
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

// Swap in the per-page SEO values now that the controller has had a chance
// to set them (Seo::setTitle()/setDescription()/setImage()) — see the
// comment in view/head.php for why this can't just be echoed there directly.
// REQUEST_URI already carries whatever subdirectory the app is served under
// (e.g. /codemoi1/?act=product) since mod_rewrite forwards to public/
// internally without changing what the browser actually requested — see
// the root .htaccess — so it must NOT be prefixed again here.
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$canonical = $scheme . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$page = ob_get_clean();
$page = str_replace(
    ['__SEO_TITLE__', '__SEO_DESCRIPTION__', '__SEO_IMAGE__', '__SEO_CANONICAL__'],
    [e(Seo::title()), e(Seo::description()), e(Seo::image()), e($canonical)],
    $page
);
echo $page;
