<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordController;
use Illuminate\Support\Facades\Route;

// Placeholder — replaced by a real HomeController in Phase 4 Group B
// (catalog). Kept here now only so route('home') resolves for the
// post-login/post-logout redirects this group (Auth + Account) needs.
Route::get('/', function () {
    return view('welcome');
})->name('home');

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
