<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Server-side mirror of the client-side rules (`src/js/form-validate.js` in
 * the legacy app) — ported from `Codemoi\Controller\AuthController::validateRegistration()`.
 */
class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_name' => ['required', 'string', 'min:3'],
            'full_name' => ['required', 'string', 'min:2'],
            'email_user' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6'],
            'province' => ['required', 'string'],
            'ward' => ['required', 'string'],
            'address_detail' => ['required', 'string', 'min:3'],
            'phone_user' => ['required', 'regex:/^(\+?84|0)\d{9,10}$/'],
            'sex' => ['nullable', 'in:0,1'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_name.required' => 'Tên đăng nhập phải có ít nhất 3 ký tự',
            'user_name.min' => 'Tên đăng nhập phải có ít nhất 3 ký tự',
            'full_name.required' => 'Vui lòng nhập họ tên hợp lệ',
            'full_name.min' => 'Vui lòng nhập họ tên hợp lệ',
            'email_user.required' => 'Địa chỉ email không hợp lệ',
            'email_user.email' => 'Địa chỉ email không hợp lệ',
            'password.required' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'province.required' => 'Vui lòng chọn tỉnh/thành phố',
            'ward.required' => 'Vui lòng chọn xã/phường',
            'address_detail.required' => 'Vui lòng nhập địa chỉ chi tiết hợp lệ',
            'address_detail.min' => 'Vui lòng nhập địa chỉ chi tiết hợp lệ',
            'phone_user.required' => 'Số điện thoại không hợp lệ',
            'phone_user.regex' => 'Số điện thoại không hợp lệ',
        ];
    }
}
