@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-md text-center">
    <h1 class="mb-4 font-heading text-2xl font-semibold text-ink-900">Thanh toán chuyển khoản</h1>
    <img src="{{ $qrUrl }}" alt="VietQR" class="mx-auto rounded-md border border-ink-200">
    <p class="mt-4 text-sm text-ink-600">Nội dung chuyển khoản: <strong>{{ $memo }}</strong></p>
    <p class="text-sm text-ink-600">Số tiền: <strong>{{ number_format($amount) }}₫</strong></p>

    <form action="{{ route('checkout.qr.confirm') }}" method="post" class="mt-6">
        @csrf
        <button type="submit" class="btn-boutique w-full rounded-md px-5 py-2.5 text-sm font-semibold">Đã Chuyển Khoản</button>
    </form>
</div>
@endsection
