@extends('layouts.app')

@section('title', 'Quên mật khẩu - Turbotech')

@section('content')
<div class="mx-auto max-w-md">
    <span class="eyebrow justify-center">Khôi phục tài khoản</span>
    <h1 class="mb-6 mt-2 text-center font-heading text-2xl font-semibold text-ink-900">Quên mật khẩu</h1>

    <form action="{{ route('password.forgot.submit') }}" method="post" class="space-y-4 rounded-md border border-ink-200 bg-white p-7 shadow-sm">
        @csrf
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Email hoặc số điện thoại</label>
            <input type="text" name="identifier" required class="input-boutique">
            @error('identifier') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="btn-boutique w-full rounded-md px-5 py-3 text-sm">Gửi mã xác nhận</button>
    </form>
</div>
@endsection
