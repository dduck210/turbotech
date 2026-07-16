@extends('layouts.app')

@section('content')
<h1 class="mb-6 font-heading text-2xl font-semibold text-ink-900">Giỏ hàng</h1>

@if (count($items) === 0)
    <p class="text-ink-500">Giỏ hàng của bạn đang trống.</p>
    <a href="{{ route('product.index') }}" class="mt-4 inline-block text-brand-600 underline">Tiếp tục mua sắm</a>
@else
    <div class="overflow-x-auto rounded-md border border-ink-200 bg-white">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b border-ink-200 text-xs font-semibold uppercase text-ink-500">
                    <th class="p-4">Sản phẩm</th>
                    <th class="p-4">Đơn giá</th>
                    <th class="p-4">Số lượng</th>
                    <th class="p-4">Thành tiền</th>
                    <th class="p-4"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink-100">
                @foreach ($items as $index => $line)
                    <tr>
                        <td class="flex items-center gap-3 p-4">
                            <img src="{{ asset('admin/uploads/'.$line['img_pro']) }}" class="h-14 w-14 rounded-md object-cover">
                            <span class="font-medium text-ink-900">{{ $line['name_pro'] }}</span>
                        </td>
                        <td class="p-4">{{ number_format($line['price']) }}₫</td>
                        <td class="p-4">
                            <form action="{{ route('cart.edit') }}" method="post" class="cart-qty-form">
                                @csrf
                                <input type="hidden" name="code" value="{{ $index }}">
                                <input type="number" name="quantity" value="{{ $line['quantity'] }}" min="0"
                                    class="w-16 rounded-md border border-ink-300 px-2 py-1 text-sm" onchange="this.form.submit()">
                            </form>
                        </td>
                        <td class="p-4 font-semibold text-brand-600">{{ number_format($line['total']) }}₫</td>
                        <td class="p-4">
                            <a href="{{ route('cart.remove', ['idcart' => $index]) }}" class="text-red-600 hover:underline">Xóa</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex items-center justify-between rounded-md border border-ink-200 bg-white p-4">
        <span class="text-lg font-semibold text-ink-900">Tổng cộng: <span class="text-brand-600">{{ number_format($total) }}₫</span></span>
        <a href="{{ route('checkout.show') }}" class="btn-boutique rounded-md px-6 py-2.5 text-sm font-semibold">Tiến hành thanh toán</a>
    </div>
@endif
@endsection
