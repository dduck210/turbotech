@extends('admin.layouts.app')

@section('content')
<h1 class="mb-6 font-heading text-2xl font-semibold text-ink-900">Chi tiết đơn hàng #{{ $order->bill_code }}</h1>

<div class="max-w-2xl rounded-md border border-ink-300 bg-white p-6">
    <p class="mb-2 text-sm"><strong>Khách hàng:</strong> {{ $order->full_name }} ({{ $order->user_name }})</p>
    <p class="mb-2 text-sm"><strong>Địa chỉ:</strong> {{ $order->address }}</p>
    <p class="mb-2 text-sm"><strong>Điện thoại:</strong> {{ $order->phone }}</p>
    <p class="mb-4 text-sm"><strong>Email:</strong> {{ $order->email }}</p>

    <table class="w-full text-left text-sm">
        <thead>
            <tr class="border-b border-ink-200 text-xs font-semibold uppercase text-ink-500">
                <th class="py-2">Sản phẩm</th><th class="py-2">Đơn giá</th><th class="py-2">SL</th><th class="py-2">Thành tiền</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-ink-100">
            @foreach ($order->items as $line)
                <tr>
                    <td class="py-2">{{ $line->name_pro }}</td>
                    <td class="py-2">{{ number_format($line->price_pro) }}₫</td>
                    <td class="py-2">{{ $line->quantity }}</td>
                    <td class="py-2">{{ number_format($line->total_amount) }}₫</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4 text-right text-lg font-semibold text-brand-600">Tổng cộng: {{ number_format($order->total_amount) }}₫</div>
</div>
@endsection
