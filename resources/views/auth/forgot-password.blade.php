@extends('layouts.app')

@section('title', 'Quên mật khẩu - Turbotech')

@section('content')
<div class="mx-auto max-w-md">
    <h1 class="mb-6 font-heading text-2xl font-semibold text-ink-900">Quên mật khẩu</h1>

    <form action="{{ route('password.forgot.submit') }}" method="post" class="space-y-4 rounded-md border border-ink-200 bg-white p-6">
        @csrf
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Email hoặc số điện thoại</label>
            <input type="text" name="identifier" required
                class="w-full rounded-md border border-ink-300 px-3.5 py-2.5 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500">
            @error('identifier') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="btn-boutique w-full rounded-md px-5 py-2.5 text-sm font-semibold">Gửi mã xác nhận</button>
    </form>
</div>
@endsection
