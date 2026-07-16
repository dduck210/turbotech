<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
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
});

// Placeholder — replaced by the real admin dashboard in Phase 4 Group F.
// Needed now so AuthController::login()'s role=1 redirect resolves.
Route::get('/admin/dashboard', function () {
    return 'Admin dashboard placeholder — built in Phase 4 Group F.';
})->middleware(['auth', 'admin'])->name('admin.dashboard');

// Placeholder — replaced by the real CartController in Phase 4 Group C.
// Needed now so product/detail.blade.php's add-to-cart form resolves.
Route::post('/cart/add', function () {
    abort(501, 'Giỏ hàng chưa sẵn sàng — sẽ có ở Phase 4 Group C.');
})->name('cart.add');
