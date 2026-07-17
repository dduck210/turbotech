@extends('layouts.app')

@section('title', 'Đăng nhập - Turbotech')

@section('content')
<div class="mx-auto max-w-md">
    <span class="eyebrow justify-center">Chào mừng trở lại</span>
    <h1 class="mb-6 mt-2 text-center font-heading text-2xl font-semibold text-ink-900">Đăng nhập</h1>

    <form action="{{ route('login') }}" method="post" class="space-y-4 rounded-md border border-ink-200 bg-white p-7 shadow-sm">
        @csrf
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Tên đăng nhập hoặc Email</label>
            <input type="text" name="user_name" value="{{ old('user_name') }}" required autofocus class="input-boutique">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Mật khẩu</label>
            <input type="password" name="password" required class="input-boutique">
        </div>
        @error('login') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

        <button type="submit" class="btn-boutique w-full rounded-md px-5 py-3 text-sm">Đăng nhập</button>
        <div class="flex justify-between text-sm">
            <a href="{{ route('register.show') }}" class="text-brand-600 hover:underline">Đăng ký tài khoản</a>
            <a href="{{ route('password.forgot') }}" class="text-brand-600 hover:underline">Quên mật khẩu?</a>
        </div>
    </form>
</div>
@endsection
