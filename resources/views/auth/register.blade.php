@extends('layouts.app')

@section('title', 'Đăng ký tài khoản - Turbotech')

@section('content')
<div class="mx-auto max-w-md">
    <span class="eyebrow justify-center">Gia nhập Turbotech</span>
    <h1 class="mb-6 mt-2 text-center font-heading text-2xl font-semibold text-ink-900">Đăng ký tài khoản</h1>

    @if ($errors->has('duplicate'))
        <div class="mb-4 rounded-md border border-red-300 bg-red-50 p-3 text-sm text-red-700">
            {{ $errors->first('duplicate') }}
            <a href="{{ route('password.forgot') }}" class="underline">Quên mật khẩu?</a>
        </div>
    @endif

    <form action="{{ route('register') }}" method="post" class="space-y-4 rounded-md border border-ink-200 bg-white p-7 shadow-sm">
        @csrf
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Tên đăng nhập</label>
            <input type="text" name="user_name" value="{{ old('user_name') }}" required
                class="input-boutique">
            @error('user_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Họ và tên</label>
            <input type="text" name="full_name" value="{{ old('full_name') }}" required
                class="input-boutique">
            @error('full_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Email</label>
            <input type="email" name="email_user" value="{{ old('email_user') }}" required
                class="input-boutique">
            @error('email_user') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Mật khẩu</label>
            <input type="password" name="password" required
                class="input-boutique">
            @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="mb-1 block text-sm font-medium text-ink-700">Tỉnh/thành phố</label>
                <input type="text" name="province" value="{{ old('province') }}" required
                    class="input-boutique">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-ink-700">Xã/phường</label>
                <input type="text" name="ward" value="{{ old('ward') }}" required
                    class="input-boutique">
            </div>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Địa chỉ chi tiết</label>
            <input type="text" name="address_detail" value="{{ old('address_detail') }}" required
                class="input-boutique">
            @error('address_detail') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Số điện thoại</label>
            <input type="text" name="phone_user" value="{{ old('phone_user') }}" required
                class="input-boutique">
            @error('phone_user') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="btn-boutique w-full rounded-md px-5 py-3 text-sm">Đăng ký</button>
        <p class="text-center text-sm text-ink-600">Đã có tài khoản? <a href="{{ route('login.show') }}" class="text-brand-600 hover:underline">Đăng nhập</a></p>
    </form>
</div>
@endsection
