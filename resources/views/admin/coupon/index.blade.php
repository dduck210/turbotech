@extends('admin.layouts.app')

@section('page-title', 'Mã giảm giá')

@section('content')
<div class="mb-6 flex items-center justify-end">
    <a href="{{ route('admin.coupons.create') }}" class="btn-boutique rounded-md px-4 py-2 text-sm"><i class="fas fa-plus"></i> Thêm mã</a>
</div>
<div class="overflow-x-auto rounded-md border border-ink-300 bg-white">
    <table class="w-full text-left text-sm">
        <thead>
            <tr class="border-b border-ink-300 text-xs font-semibold uppercase text-ink-500">
                <th class="p-4">Mã</th><th class="p-4">Loại</th><th class="p-4">Giá trị</th><th class="p-4">Đã dùng/Giới hạn</th><th class="p-4">Trạng thái</th><th class="p-4"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-ink-200">
            @foreach ($coupons as $coupon)
                <tr>
                    <td class="p-4 font-mono">{{ $coupon->code }}</td>
                    <td class="p-4">{{ (int) $coupon->discount_type === 1 ? '%' : 'VNĐ' }}</td>
                    <td class="p-4">{{ $coupon->discount_value }}{{ (int) $coupon->discount_type === 1 ? '%' : 'đ' }}</td>
                    <td class="p-4">{{ $coupon->used_count }}/{{ $coupon->usage_limit ?: '∞' }}</td>
                    <td class="p-4">
                        @if ((int) $coupon->status === 1)
                            <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">Đang hoạt động</span>
                        @else
                            <span class="rounded-full bg-ink-100 px-2.5 py-1 text-xs font-semibold text-ink-700">Tắt</span>
                        @endif
                    </td>
                    <td class="p-4">
                        <a href="{{ route('admin.coupons.edit', $coupon->id_coupon) }}" class="text-amber-600 hover:underline">Sửa</a>
                        <form action="{{ route('admin.coupons.destroy', $coupon->id_coupon) }}" method="post" class="inline" onsubmit="return confirm('Xóa mã này?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="ml-3 text-red-600 hover:underline">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
