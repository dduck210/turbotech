@extends('layouts.app')

@section('content')
<span class="eyebrow">Giỏ hàng của bạn</span>
<h1 class="mb-6 mt-2 font-heading text-2xl font-semibold text-ink-900">Giỏ hàng</h1>

@if (count($items) === 0)
    <div class="rounded-md border border-ink-200 bg-white p-10 text-center">
        <i class="fas fa-shopping-bag mb-3 text-3xl text-ink-300"></i>
        <p class="text-ink-500">Giỏ hàng của bạn đang trống.</p>
        <a href="{{ route('product.index') }}" class="btn-boutique mt-5 inline-block rounded-md px-6 py-2.5 text-sm">Tiếp tục mua sắm</a>
    </div>
@else
    <div class="overflow-x-auto rounded-md border border-ink-200 bg-white">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b border-ink-200 text-xs font-semibold uppercase tracking-wide text-ink-500">
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
                            <img src="{{ asset('storage/products/'.$line['img_pro']) }}" class="h-14 w-14 rounded-md border border-ink-200 object-cover">
                            <span class="font-medium text-ink-900">{{ $line['name_pro'] }}</span>
                        </td>
                        <td class="p-4 tabular-nums">{{ number_format($line['price']) }}₫</td>
                        <td class="p-4">
                            <form action="{{ route('cart.edit') }}" method="post" class="cart-qty-form">
                                @csrf
                                <input type="hidden" name="code" value="{{ $index }}">
                                <input type="number" name="quantity" value="{{ $line['quantity'] }}" min="0"
                                    class="input-boutique w-16 py-1.5" onchange="this.form.submit()">
                            </form>
                        </td>
                        <td class="price p-4 text-base font-semibold">{{ number_format($line['total']) }}₫</td>
                        <td class="p-4">
                            <a href="{{ route('cart.remove', ['idcart' => $index]) }}" class="text-red-600 hover:underline">Xóa</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex flex-wrap items-center justify-between gap-4 rounded-md border border-ink-200 bg-white p-5">
        <span class="text-lg font-semibold text-ink-900">Tổng cộng: <span class="price text-xl">{{ number_format($total) }}₫</span></span>
        <a href="{{ route('checkout.show') }}" class="btn-boutique rounded-md px-7 py-3 text-sm">Tiến hành thanh toán</a>
    </div>
@endif
@endsection
