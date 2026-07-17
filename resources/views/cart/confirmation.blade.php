@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-2xl text-center">
    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
        <i class="fas fa-check text-2xl"></i>
    </div>
    <h1 class="mb-2 mt-5 font-heading text-2xl font-semibold text-ink-900">Đặt hàng thành công!</h1>
    <p class="text-sm text-ink-500">Cảm ơn bạn đã mua sắm tại Turbotech.</p>

    @if ($order)
        <div class="mt-6 rounded-md border border-ink-200 bg-white p-6 text-left">
            <dl class="grid grid-cols-1 gap-2 text-sm sm:grid-cols-2">
                <div><dt class="text-ink-500">Mã đơn hàng</dt><dd class="font-medium text-ink-900">{{ $order->bill_code }}</dd></div>
                <div><dt class="text-ink-500">Người nhận</dt><dd class="font-medium text-ink-900">{{ $order->full_name }}</dd></div>
                <div class="sm:col-span-2"><dt class="text-ink-500">Địa chỉ</dt><dd class="font-medium text-ink-900">{{ $order->address }}</dd></div>
                <div><dt class="text-ink-500">Tổng tiền</dt><dd class="price text-lg font-semibold">{{ number_format($order->total_amount) }}₫</dd></div>
            </dl>

            <table class="mt-5 w-full border-t border-ink-200 pt-3 text-left text-sm">
                <tbody class="divide-y divide-ink-100">
                    @foreach ($items as $line)
                        <tr>
                            <td class="py-2">{{ $line->name_pro }} <span class="text-ink-400">x{{ $line->quantity }}</span></td>
                            <td class="py-2 text-right tabular-nums">{{ number_format($line->total_amount) }}₫</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="mt-6 text-ink-500">Không tìm thấy thông tin đơn hàng.</p>
    @endif

    <a href="{{ route('home') }}" class="btn-boutique mt-8 inline-block rounded-md px-7 py-3 text-sm">Về trang chủ</a>
</div>
@endsection
