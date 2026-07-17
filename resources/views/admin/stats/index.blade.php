@extends('admin.layouts.app')

@section('page-title', 'Thống kê')

@section('content')
<form method="get" class="mb-8 flex flex-wrap items-end gap-3 rounded-md border border-ink-300 bg-white p-4 shadow-sm">
    <div>
        <label class="mb-1 block text-sm font-medium text-ink-700">Từ ngày</label>
        <input type="date" name="from_date" value="{{ $fromDate }}" class="input-boutique">
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-ink-700">Đến ngày</label>
        <input type="date" name="to_date" value="{{ $toDate }}" class="input-boutique">
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-ink-700">Sắp xếp sản phẩm bán chạy</label>
        <select name="sort_product" class="input-boutique">
            <option value="DESC" @selected($sortProduct === 'DESC')>Bán chạy nhất</option>
            <option value="ASC" @selected($sortProduct === 'ASC')>Bán chậm nhất</option>
        </select>
    </div>
    <button type="submit" class="btn-boutique rounded-md px-5 py-2.5 text-sm">Lọc</button>
</form>

<div class="mb-8">
    <span class="eyebrow">Đã thanh toán &middot; không tính đơn đã hủy</span>
    <h2 class="mb-3 mt-1 font-heading text-lg font-semibold text-ink-900">Doanh thu theo ngày</h2>
    <div class="overflow-x-auto rounded-md border border-ink-300 bg-white">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b border-ink-300 text-xs font-semibold uppercase text-ink-500">
                    <th class="p-4">Ngày</th><th class="p-4">Số đơn</th><th class="p-4">Doanh thu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink-200">
                @forelse ($revenueStats as $row)
                    <tr>
                        <td class="p-4">{{ $row->order_day }}</td>
                        <td class="p-4 tabular-nums">{{ $row->total_orders }}</td>
                        <td class="price p-4 font-semibold">{{ number_format($row->total_revenue) }}đ</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="p-4 text-center text-ink-500">Không có dữ liệu.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mb-8">
    <span class="eyebrow">Xếp hạng</span>
    <h2 class="mb-3 mt-1 font-heading text-lg font-semibold text-ink-900">Sản phẩm bán chạy</h2>
    <div class="overflow-x-auto rounded-md border border-ink-300 bg-white">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b border-ink-300 text-xs font-semibold uppercase text-ink-500">
                    <th class="p-4">Sản phẩm</th><th class="p-4">Số lượng bán</th><th class="p-4">Doanh thu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink-200">
                @forelse ($productStats as $row)
                    <tr>
                        <td class="p-4">{{ $row->name_pro }}</td>
                        <td class="p-4 tabular-nums">{{ $row->total_sold }}</td>
                        <td class="price p-4 font-semibold">{{ number_format($row->total_revenue) }}đ</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="p-4 text-center text-ink-500">Không có dữ liệu.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div>
    <span class="eyebrow">Cảnh báo tồn kho</span>
    <h2 class="mb-3 mt-1 font-heading text-lg font-semibold text-ink-900">Tồn kho</h2>
    <div class="overflow-x-auto rounded-md border border-ink-300 bg-white">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b border-ink-300 text-xs font-semibold uppercase text-ink-500">
                    <th class="p-4">Sản phẩm</th><th class="p-4">Tồn kho</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink-200">
                @foreach ($inventoryStats as $product)
                    <tr>
                        <td class="p-4">{{ $product->name_pro }}</td>
                        <td class="p-4 tabular-nums {{ $product->stock <= 5 ? 'font-semibold text-red-600' : '' }}">{{ $product->stock }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
