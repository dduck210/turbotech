@extends('admin.layouts.app')

@section('page-title', 'Đơn hàng')

@section('content')
@php
    $statusLabels = [0 => 'Đơn hàng mới', 1 => 'Đang xử lí', 2 => 'Đang giao hàng', 3 => 'Đã giao hàng', 4 => 'Đã hủy'];
@endphp

<form action="{{ route('admin.orders.index') }}" method="get" class="mb-6 flex flex-wrap gap-3 rounded-md border border-ink-300 bg-white p-4">
    <input type="text" name="keyword" value="{{ $keyword }}" placeholder="Tên khách, SĐT..." class="input-boutique">
    <select name="status" class="input-boutique">
        <option value="-1" @selected($status === -1)>Tất cả</option>
        @foreach ($statusLabels as $value => $label)
            <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
        @endforeach
    </select>
    <input type="date" name="from_date" value="{{ $fromDate }}" class="input-boutique">
    <input type="date" name="to_date" value="{{ $toDate }}" class="input-boutique">
    <button type="submit" class="rounded-md border border-ink-300 px-4 py-2 text-sm font-medium transition-colors hover:border-brand-500 hover:text-brand-600">Lọc</button>
</form>

<div class="overflow-x-auto rounded-md border border-ink-300 bg-white">
    <table class="w-full text-left text-sm">
        <thead>
            <tr class="border-b border-ink-300 text-xs font-semibold uppercase text-ink-500">
                <th class="p-4">Mã đơn</th><th class="p-4">Khách hàng</th><th class="p-4">Tổng tiền</th><th class="p-4">Trạng thái</th><th class="p-4">Ngày đặt</th><th class="p-4"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-ink-200">
            @foreach ($orders as $order)
                <tr>
                    <td class="p-4">{{ $order->bill_code }}</td>
                    <td class="p-4">{{ $order->full_name }}</td>
                    <td class="price p-4 font-semibold">{{ number_format($order->total_amount) }}₫</td>
                    <td class="p-4">
                        @php($statusColors = [0 => 'bg-ink-100 text-ink-700', 1 => 'bg-brand-100 text-brand-700', 2 => 'bg-blue-100 text-blue-700', 3 => 'bg-emerald-100 text-emerald-700', 4 => 'bg-red-100 text-red-700'])
                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusColors[$order->status] ?? 'bg-ink-100 text-ink-700' }}">{{ $statusLabels[$order->status] ?? 'Đơn hàng mới' }}</span>
                    </td>
                    <td class="p-4">{{ $order->order_date?->format('d/m/Y H:i') }}</td>
                    <td class="p-4">
                        <div class="flex items-center gap-2">
                            @if ((int) $order->status === 0)
                                <form action="{{ route('admin.orders.approve', $order->id_bill) }}" method="post" onsubmit="return confirm('Duyệt đơn hàng này?');">
                                    @csrf<button type="submit" class="text-blue-600 hover:underline" title="Duyệt">Duyệt</button>
                                </form>
                            @elseif ((int) $order->status === 1)
                                <form action="{{ route('admin.orders.ship', $order->id_bill) }}" method="post" onsubmit="return confirm('Chuyển sang giao hàng?');">
                                    @csrf<button type="submit" class="text-indigo-600 hover:underline">Giao hàng</button>
                                </form>
                            @endif
                            <a href="{{ route('admin.orders.edit', $order->id_bill) }}" class="text-amber-600 hover:underline">Sửa</a>
                            <a href="{{ route('admin.orders.show', $order->id_bill) }}" class="text-emerald-600 hover:underline">Chi tiết</a>
                            @if (in_array((int) $order->status, [0, 1], true))
                                <form action="{{ route('admin.orders.cancel', $order->id_bill) }}" method="post" onsubmit="return confirm('Hủy đơn hàng này?');">
                                    @csrf<button type="submit" class="text-red-600 hover:underline">Hủy</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
