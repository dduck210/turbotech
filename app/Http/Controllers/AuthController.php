<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Ported from `Codemoi\Controller\AuthController` (legacy `src/Controller/AuthController.php`).
 */
class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $address = trim($data['address_detail']).', '.trim($data['ward']).', '.trim($data['province']);

        // Checked after format validation (so "invalid phone" still wins over
        // "phone taken") but before insert — mirrors the legacy priority
        // order (phone -> email -> username) so the error shown always
        // points at the single most likely field a returning user typed.
        $duplicateField = $this->findDuplicateField($data['user_name'], $data['email_user'], $data['phone_user']);

        if ($duplicateField !== null) {
            $message = $duplicateField === 'username'
                ? 'Tên đăng nhập này đã được sử dụng, vui lòng chọn tên khác'
                : 'Số điện thoại hoặc email đã được sử dụng, vui lòng đăng nhập hoặc dùng "Quên mật khẩu?"';

            return back()->withInput()->with('duplicateField', $duplicateField)->withErrors(['duplicate' => $message]);
        }

        User::create([
            'user_name' => $data['user_name'],
            'full_name' => $data['full_name'],
            'email_user' => $data['email_user'],
            'password' => Hash::make($data['password']),
            'sex' => (int) ($data['sex'] ?? 0),
            'address' => $address,
            'phone_user' => $data['phone_user'],
            'img_user' => '',
            'role' => 0,
        ]);

        return redirect()->route('login')->with('flash_success', 'Đăng ký tài khoản thành công! Vui lòng đăng nhập');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        $user = User::where('user_name', $data['user_name'])
            ->orWhere('email_user', $data['user_name'])
            ->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return back()->withErrors(['login' => 'Tài khoản sai hoặc không tồn tại!']);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(
            (int) $user->role === 1 ? route('admin.dashboard') : route('home')
        )->with('flash_success', 'Đăng nhập thành công!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Mirrors `Codemoi\Model\User::findDuplicateField()` — checked in order
     * phone -> email -> username so the message always points at the
     * single most likely field a returning user typed. `user.phone_user`
     * has no DB unique constraint (only `user_name`/`email_user` do), so
     * this app-level check is the only thing catching phone duplicates.
     */
    private function findDuplicateField(string $userName, string $email, string $phone): ?string
    {
        if (User::where('phone_user', $phone)->exists()) {
            return 'phone';
        }
        if (User::where('email_user', $email)->exists()) {
            return 'email';
        }
        if (User::where('user_name', $userName)->exists()) {
            return 'username';
        }

        return null;
    }
}
