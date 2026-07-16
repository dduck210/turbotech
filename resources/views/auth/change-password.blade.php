@extends('layouts.app')

@section('title', 'Đặt lại mật khẩu - Turbotech')

@section('content')
<div class="mx-auto max-w-md">
    <h1 class="mb-6 font-heading text-2xl font-semibold text-ink-900">Tạo mật khẩu mới</h1>

    <form action="{{ route('password.change.submit') }}" method="post" class="space-y-4 rounded-md border border-ink-200 bg-white p-6">
        @csrf
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Mật khẩu mới</label>
            <input type="password" name="newpass" required
                class="w-full rounded-md border border-ink-300 px-3.5 py-2.5 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500">
            @error('newpass') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Nhập lại mật khẩu</label>
            <input type="password" name="repass" required
                class="w-full rounded-md border border-ink-300 px-3.5 py-2.5 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500">
            @error('repass') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="btn-boutique w-full rounded-md px-5 py-2.5 text-sm font-semibold">Đặt lại mật khẩu</button>
    </form>
</div>
@endsection
