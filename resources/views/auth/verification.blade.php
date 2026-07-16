@extends('layouts.app')

@section('title', 'Xác nhận mã - Turbotech')

@section('content')
<div class="mx-auto max-w-md">
    <h1 class="mb-6 font-heading text-2xl font-semibold text-ink-900">Nhập mã xác nhận</h1>
    <p class="mb-4 text-sm text-ink-600">Mã xác nhận đã được gửi qua email, có hiệu lực trong 10 phút.</p>

    <form action="{{ route('password.verify.submit') }}" method="post" class="space-y-4 rounded-md border border-ink-200 bg-white p-6">
        @csrf
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Mã xác nhận</label>
            <input type="text" name="ma" required maxlength="6"
                class="w-full rounded-md border border-ink-300 px-3.5 py-2.5 text-center text-lg tracking-[0.5em] focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500">
            @error('code') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="btn-boutique w-full rounded-md px-5 py-2.5 text-sm font-semibold">Xác nhận</button>
    </form>
</div>
@endsection
