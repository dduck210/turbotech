@extends('admin.layouts.app')

@section('content')
<h1 class="mb-6 font-heading text-2xl font-semibold text-ink-900">Cập nhật đơn hàng #{{ $order->bill_code }}</h1>

<div class="max-w-lg rounded-md border border-ink-300 bg-white p-6">
    <p class="mb-2 text-sm"><strong>Khách hàng:</strong> {{ $order->full_name }}</p>
    <p class="mb-2 text-sm"><strong>Địa chỉ:</strong> {{ $order->address }}</p>
    <p class="mb-4 text-sm"><strong>Tổng tiền:</strong> {{ number_format($order->total_amount) }}₫</p>

    <form action="{{ route('admin.orders.update', $order->id_bill) }}" method="post" class="space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Trạng thái đơn hàng</label>
            <select name="status" class="w-full rounded-md border border-ink-300 px-3.5 py-2.5 text-sm">
                <option value="0" @selected($order->status === 0)>Đơn hàng mới</option>
                <option value="1" @selected($order->status === 1)>Đang xử lí</option>
                <option value="2" @selected($order->status === 2)>Đang giao hàng</option>
                <option value="3" @selected($order->status === 3)>Đã giao hàng</option>
                <option value="4" @selected($order->status === 4)>Đã hủy</option>
            </select>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Thanh toán</label>
            <select name="status_pay" class="w-full rounded-md border border-ink-300 px-3.5 py-2.5 text-sm">
                <option value="0" @selected((int) $order->status_pay === 0)>Chưa thanh toán</option>
                <option value="1" @selected((int) $order->status_pay === 1)>Đã thanh toán</option>
            </select>
        </div>
        <button type="submit" class="btn-boutique rounded-md px-5 py-2.5 text-sm font-semibold">Lưu</button>
    </form>
</div>
@endsection
