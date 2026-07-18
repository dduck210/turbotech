<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\BillController as AdminBillController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\QuestionController as AdminQuestionController;
use App\Http\Controllers\Admin\StatsController as AdminStatsController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::get('/products', [ProductController::class, 'index'])->name('product.index');
Route::get('/products/{idpro}', [ProductController::class, 'show'])->name('product.show');
Route::post('/products/{idpro}/reviews', [ProductController::class, 'submitReview'])
    ->middleware('auth')->name('product.reviews.store');

Route::get('/introduce', [PageController::class, 'introduce'])->name('introduce');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');
Route::get('/question', [QuestionController::class, 'index'])->name('question.index');
Route::post('/question', [QuestionController::class, 'submit'])->name('question.submit');

Route::get('/cart', [CartController::class, 'view'])->name('cart.view');
Route::post('/cart/edit', [CartController::class, 'edit'])->name('cart.edit');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register.show');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login.show');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login');

    Route::get('/forgot-password', [PasswordController::class, 'showForgot'])->name('password.forgot');
    Route::post('/forgot-password', [PasswordController::class, 'forgot'])->middleware('throttle:3,1')->name('password.forgot.submit');
    Route::get('/verify-code', [PasswordController::class, 'showVerify'])->name('password.verify');
    // IP-based, on top of PasswordController::verify()'s own session-attempt
    // counter — that counter resets for free on a fresh session (no cookie
    // sent), so it alone can't stop a scripted attacker from grinding
    // through the 6-digit code with unlimited attempts.
    Route::post('/verify-code', [PasswordController::class, 'verify'])->middleware('throttle:5,1')->name('password.verify.submit');
    Route::get('/change-password', [PasswordController::class, 'showChange'])->name('password.change');
    Route::post('/change-password', [PasswordController::class, 'change'])->name('password.change.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/account', [AccountController::class, 'index'])->name('account.index');
    Route::post('/account/profile', [AccountController::class, 'updateProfile'])->name('account.profile');
    Route::post('/account/password', [AccountController::class, 'changePassword'])->name('account.password');
    Route::post('/account/orders/cancel', [AccountController::class, 'cancelOrder'])->name('account.orders.cancel');

    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout/coupon/apply', [CheckoutController::class, 'applyCoupon'])->name('checkout.coupon.apply');
    Route::post('/checkout/coupon/remove', [CheckoutController::class, 'removeCoupon'])->name('checkout.coupon.remove');
    Route::post('/checkout/confirm', [CheckoutController::class, 'confirm'])->name('checkout.confirm');
    Route::get('/checkout/confirmation', [CheckoutController::class, 'confirmation'])->name('checkout.confirmation');
    Route::get('/checkout/qr', [CheckoutController::class, 'qr'])->name('checkout.qr');
    Route::post('/checkout/qr/confirm', [CheckoutController::class, 'confirmTransfer'])->name('checkout.qr.confirm');
});

Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');

    Route::resource('categories', AdminCategoryController::class)->except('show');
    Route::resource('products', AdminProductController::class)->except('show');

    Route::get('/orders', [AdminBillController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminBillController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/edit', [AdminBillController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{order}', [AdminBillController::class, 'update'])->name('orders.update');
    Route::post('/orders/{order}/approve', [AdminBillController::class, 'approve'])->name('orders.approve');
    Route::post('/orders/{order}/ship', [AdminBillController::class, 'ship'])->name('orders.ship');
    Route::post('/orders/{order}/cancel', [AdminBillController::class, 'cancel'])->name('orders.cancel');

    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    Route::resource('coupons', AdminCouponController::class)->except('show');

    Route::get('/comments', [AdminCommentController::class, 'index'])->name('comments.index');
    Route::delete('/comments/{comment}', [AdminCommentController::class, 'destroy'])->name('comments.destroy');

    Route::get('/questions', [AdminQuestionController::class, 'index'])->name('questions.index');
    Route::delete('/questions/{question}', [AdminQuestionController::class, 'destroy'])->name('questions.destroy');
    Route::get('/questions/{question}/reply', [AdminQuestionController::class, 'showReply'])->name('questions.reply');
    Route::post('/questions/{question}/reply', [AdminQuestionController::class, 'sendReply'])->name('questions.reply.send');

    Route::get('/stats', AdminStatsController::class)->name('stats.index');
});
