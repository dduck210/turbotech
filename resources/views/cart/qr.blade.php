@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-md text-center">
    <span class="eyebrow justify-center">Bước cuối cùng</span>
    <h1 class="mb-4 mt-2 font-heading text-2xl font-semibold text-ink-900">Thanh toán chuyển khoản</h1>
    <div class="inline-block rounded-md border border-ink-200 bg-white p-4">
        <img src="{{ $qrUrl }}" alt="VietQR" class="mx-auto rounded-md">
    </div>
    <p class="mt-5 text-sm text-ink-600">Nội dung chuyển khoản: <strong class="text-ink-900">{{ $memo }}</strong></p>
    <p class="text-sm text-ink-600">Số tiền: <strong class="price">{{ number_format($amount) }}₫</strong></p>

    <form action="{{ route('checkout.qr.confirm') }}" method="post" class="mt-6">
        @csrf
        <button type="submit" class="btn-boutique w-full rounded-md px-5 py-3 text-sm">Đã Chuyển Khoản</button>
    </form>
</div>
@endsection
