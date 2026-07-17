@extends('layouts.app')

@section('title', 'Đặt lại mật khẩu - Turbotech')

@section('content')
<div class="mx-auto max-w-md">
    <span class="eyebrow justify-center">Khôi phục tài khoản</span>
    <h1 class="mb-6 mt-2 text-center font-heading text-2xl font-semibold text-ink-900">Tạo mật khẩu mới</h1>

    <form action="{{ route('password.change.submit') }}" method="post" class="space-y-4 rounded-md border border-ink-200 bg-white p-7 shadow-sm">
        @csrf
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Mật khẩu mới</label>
            <input type="password" name="newpass" required class="input-boutique">
            @error('newpass') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Nhập lại mật khẩu</label>
            <input type="password" name="repass" required class="input-boutique">
            @error('repass') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="btn-boutique w-full rounded-md px-5 py-3 text-sm">Đặt lại mật khẩu</button>
    </form>
</div>
@endsection
