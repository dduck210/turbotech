@extends('admin.layouts.app')

@section('page-title', 'Dashboard')

@section('content')
@php
    $stats = [
        ['label' => 'Người dùng', 'value' => $userCount, 'icon' => 'fa-users'],
        ['label' => 'Sản phẩm', 'value' => $productCount, 'icon' => 'fa-laptop'],
        ['label' => 'Danh mục', 'value' => $categoryCount, 'icon' => 'fa-tags'],
        ['label' => 'Đơn hàng', 'value' => $orderCount, 'icon' => 'fa-receipt'],
        ['label' => 'Bình luận', 'value' => $commentCount, 'icon' => 'fa-comments'],
    ];
    $statusLabels = [0 => 'Đơn hàng mới', 1 => 'Đang xử lí', 2 => 'Đang giao hàng', 3 => 'Đã giao hàng', 4 => 'Đã hủy'];
    $statusColors = [0 => 'bg-ink-100 text-ink-700', 1 => 'bg-brand-100 text-brand-700', 2 => 'bg-blue-100 text-blue-700', 3 => 'bg-emerald-100 text-emerald-700', 4 => 'bg-red-100 text-red-700'];
@endphp
<div class="grid grid-cols-2 gap-4 md:grid-cols-5">
    @foreach ($stats as $stat)
        <div class="card-boutique rounded-md p-5">
            <div class="flex h-9 w-9 items-center justify-center rounded-md bg-brand-100 text-brand-700">
                <i class="fas {{ $stat['icon'] }} text-sm"></i>
            </div>
            <div class="price mt-3 text-2xl font-bold">{{ $stat['value'] }}</div>
            <div class="text-sm text-ink-500">{{ $stat['label'] }}</div>
        </div>
    @endforeach
</div>
<p class="mt-6 text-sm text-ink-500">Biểu đồ doanh thu/thống kê chi tiết &mdash; xem <a href="{{ route('admin.stats.index') }}" class="text-brand-600 hover:underline">trang Thống kê</a>.</p>

<div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
    <div>
        <div class="mb-4 flex items-center justify-between">
            <h2 class="font-heading text-lg font-semibold text-ink-900">Đơn hàng gần đây</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-brand-600 hover:underline">Xem tất cả &rarr;</a>
        </div>
        <div class="overflow-hidden rounded-md border border-ink-300 bg-white">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-ink-300 text-xs font-semibold uppercase text-ink-500">
                        <th class="p-3">Mã đơn</th><th class="p-3">Khách hàng</th><th class="p-3">Tổng tiền</th><th class="p-3">Trạng thái</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-200">
                    @forelse ($recentOrders as $order)
                        <tr class="cursor-pointer transition-colors hover:bg-ink-50" onclick="location.href='{{ route('admin.orders.show', $order->id_bill) }}'">
                            <td class="p-3">{{ $order->bill_code }}</td>
                            <td class="p-3">{{ $order->full_name }}</td>
                            <td class="price p-3 font-semibold">{{ number_format($order->total_amount) }}₫</td>
                            <td class="p-3">
                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusColors[$order->status] ?? 'bg-ink-100 text-ink-700' }}">{{ $statusLabels[$order->status] ?? 'Đơn hàng mới' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="p-4 text-center text-ink-500">Chưa có đơn hàng nào.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>
        <div class="mb-4 flex items-center justify-between">
            <h2 class="font-heading text-lg font-semibold text-ink-900">Sắp hết hàng</h2>
            <a href="{{ route('admin.products.index') }}" class="text-sm font-medium text-brand-600 hover:underline">Xem tất cả &rarr;</a>
        </div>
        <div class="overflow-hidden rounded-md border border-ink-300 bg-white">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-ink-300 text-xs font-semibold uppercase text-ink-500">
                        <th class="p-3">Sản phẩm</th><th class="p-3">Tồn kho</th><th class="p-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-200">
                    @forelse ($lowStockProducts as $product)
                        <tr class="cursor-pointer transition-colors hover:bg-ink-50" onclick="location.href='{{ route('admin.products.edit', $product->id_pro) }}'">
                            <td class="p-3">{{ $product->name_pro }}</td>
                            <td class="p-3">
                                <span class="rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700">{{ $product->stock }} còn lại</span>
                            </td>
                            <td class="p-3 text-right"><a href="{{ route('admin.products.edit', $product->id_pro) }}" class="text-amber-600 hover:underline">Sửa</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="p-4 text-center text-ink-500">Không có sản phẩm nào sắp hết hàng.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
