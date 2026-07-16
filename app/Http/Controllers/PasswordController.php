<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

/**
 * Ported from `Codemoi\Controller\PasswordController` (legacy
 * `src/Controller/PasswordController.php`). OTP state stays session-based
 * (`code`, `code_expires`, `code_attempts`, `mail`, `otp_verified`) —
 * matching the legacy implementation exactly, which never used a DB table
 * for this either.
 */
class PasswordController extends Controller
{
    public function showForgot()
    {
        return view('auth.forgot-password');
    }

    public function forgot(Request $request)
    {
        $identifier = trim($request->input('identifier', ''));

        if ($identifier === '') {
            return back()->withErrors(['identifier' => 'Vui lòng nhập email hoặc số điện thoại']);
        }

        $account = User::where('email_user', $identifier)->orWhere('phone_user', $identifier)->first();

        if (! $account) {
            return back()->withErrors(['identifier' => 'Không tìm thấy tài khoản với email hoặc số điện thoại này']);
        }

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        Mail::html(
            "<p>Xin chào, chúng tôi đã nhận được yêu cầu đặt lại mật khẩu Turbotech của bạn.<br>".
            "Nhập mã sau đây để đặt lại mật khẩu: <span style='font-weight:600'>{$code}</span></p>",
            function ($message) use ($account) {
                $message->to($account->email_user)->subject('Tìm lại mật khẩu của bạn');
            }
        );

        session([
            'mail' => $account->email_user,
            'code' => $code,
            'code_expires' => time() + 600,
            'code_attempts' => 0,
        ]);
        session()->forget('otp_verified');

        return redirect()->route('password.verify');
    }

    public function showVerify()
    {
        return view('auth.verification');
    }

    public function verify(Request $request)
    {
        if (session('code_attempts', 0) >= 5) {
            return back()->withErrors(['code' => 'Bạn đã nhập sai quá 5 lần, vui lòng yêu cầu mã mới.']);
        }

        if (time() > session('code_expires', 0)) {
            return back()->withErrors(['code' => 'Mã xác nhận đã hết hạn, vui lòng yêu cầu mã mới.']);
        }

        if (! hash_equals((string) session('code', ''), (string) $request->input('ma', ''))) {
            session(['code_attempts' => session('code_attempts', 0) + 1]);

            return back()->withErrors(['code' => 'Mã xác nhận không đúng.']);
        }

        session(['otp_verified' => true]);

        return redirect()->route('password.change');
    }

    public function showChange()
    {
        if (! session('otp_verified', false)) {
            return redirect()->route('password.forgot');
        }

        return view('auth.change-password');
    }

    public function change(Request $request)
    {
        if (! session('otp_verified', false)) {
            return redirect()->route('password.forgot');
        }

        $request->validate([
            'newpass' => ['required', 'string', 'min:6'],
            'repass' => ['required', 'same:newpass'],
        ], [
            'newpass.min' => 'Mật khẩu phải có ít nhất 6 ký tự !',
            'repass.same' => 'Nhập lại mật khẩu không khớp !',
        ]);

        User::where('email_user', session('mail'))->update([
            'password' => Hash::make($request->input('newpass')),
        ]);

        session()->forget(['mail', 'otp_verified', 'code', 'code_expires', 'code_attempts']);

        return redirect()->route('login')->with('flash_success', 'Đổi mật khẩu thành công, vui lòng đăng nhập lại!');
    }
}
