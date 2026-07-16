@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-2xl text-center">
    <h1 class="mb-4 font-heading text-2xl font-semibold text-emerald-600">Đặt hàng thành công!</h1>

    @if ($order)
        <div class="rounded-md border border-ink-200 bg-white p-6 text-left">
            <p><strong>Mã đơn hàng:</strong> {{ $order->bill_code }}</p>
            <p><strong>Người nhận:</strong> {{ $order->full_name }}</p>
            <p><strong>Địa chỉ:</strong> {{ $order->address }}</p>
            <p><strong>Tổng tiền:</strong> {{ number_format($order->total_amount) }}₫</p>

            <table class="mt-4 w-full text-left text-sm">
                <tbody class="divide-y divide-ink-100">
                    @foreach ($items as $line)
                        <tr>
                            <td class="py-2">{{ $line->name_pro }} x{{ $line->quantity }}</td>
                            <td class="py-2 text-right">{{ number_format($line->total_amount) }}₫</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-ink-500">Không tìm thấy thông tin đơn hàng.</p>
    @endif

    <a href="{{ route('home') }}" class="mt-6 inline-block text-brand-600 underline">Về trang chủ</a>
</div>
@endsection
