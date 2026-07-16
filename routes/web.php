<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

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
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::get('/forgot-password', [PasswordController::class, 'showForgot'])->name('password.forgot');
    Route::post('/forgot-password', [PasswordController::class, 'forgot'])->name('password.forgot.submit');
    Route::get('/verify-code', [PasswordController::class, 'showVerify'])->name('password.verify');
    Route::post('/verify-code', [PasswordController::class, 'verify'])->name('password.verify.submit');
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

    // Placeholders — replaced by the real controllers in Phase 4 Group E/F.
    Route::get('/orders', fn () => 'Đơn hàng — Phase 4 Group E.')->name('orders.index');
    Route::get('/users', fn () => 'Người dùng — Phase 4 Group F.')->name('users.index');
    Route::get('/coupons', fn () => 'Mã giảm giá — Phase 4 Group F.')->name('coupons.index');
    Route::get('/comments', fn () => 'Bình luận — Phase 4 Group F.')->name('comments.index');
    Route::get('/questions', fn () => 'Hỏi đáp — Phase 4 Group F.')->name('questions.index');
    Route::get('/stats', fn () => 'Thống kê — Phase 4 Group F.')->name('stats.index');
});
