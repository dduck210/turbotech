@extends('layouts.app')

@section('title', 'Xác nhận mã - Turbotech')

@section('content')
<div class="mx-auto max-w-md">
    <span class="eyebrow justify-center">Khôi phục tài khoản</span>
    <h1 class="mb-2 mt-2 text-center font-heading text-2xl font-semibold text-ink-900">Nhập mã xác nhận</h1>
    <p class="mb-4 text-center text-sm text-ink-600">Mã xác nhận đã được gửi qua email, có hiệu lực trong 10 phút.</p>

    <form action="{{ route('password.verify.submit') }}" method="post" class="space-y-4 rounded-md border border-ink-200 bg-white p-7 shadow-sm">
        @csrf
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Mã xác nhận</label>
            <input type="text" name="ma" required maxlength="6" class="input-boutique text-center text-lg tracking-[0.5em]">
            @error('code') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="btn-boutique w-full rounded-md px-5 py-3 text-sm">Xác nhận</button>
    </form>
</div>
@endsection
