@extends('admin.layouts.app')

@section('page-title', 'Sửa mã giảm giá')

@section('content')
<form action="{{ route('admin.coupons.update', $coupon->id_coupon) }}" method="post" class="max-w-xl space-y-4 rounded-md border border-ink-300 bg-white p-6 shadow-sm">
    @csrf @method('PUT')
    <div>
        <label class="mb-1 block text-sm font-medium text-ink-700">Mã</label>
        <input type="text" name="code" value="{{ $coupon->code }}" required class="input-boutique">
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Loại giảm giá</label>
            <select name="discount_type" class="input-boutique">
                <option value="1" @selected((int) $coupon->discount_type === 1)>Phần trăm (%)</option>
                <option value="2" @selected((int) $coupon->discount_type === 2)>Số tiền cố định (VNĐ)</option>
            </select>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Giá trị</label>
            <input type="number" name="discount_value" value="{{ $coupon->discount_value }}" required min="1" class="input-boutique">
        </div>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Giảm tối đa</label>
            <input type="number" name="max_discount" value="{{ $coupon->max_discount }}" min="0" class="input-boutique">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Đơn tối thiểu</label>
            <input type="number" name="min_order_value" value="{{ $coupon->min_order_value }}" min="0" class="input-boutique">
        </div>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-ink-700">Áp dụng cho sản phẩm</label>
        <select name="product_id" class="input-boutique">
            <option value="0">Tất cả sản phẩm</option>
            @foreach ($products as $product)
                <option value="{{ $product->id_pro }}" @selected($product->id_pro === $coupon->product_id)>{{ $product->name_pro }}</option>
            @endforeach
        </select>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Ngày bắt đầu</label>
            <input type="datetime-local" name="start_date" value="{{ $coupon->start_date?->format('Y-m-d\TH:i') }}" required class="input-boutique">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Ngày kết thúc</label>
            <input type="datetime-local" name="end_date" value="{{ $coupon->end_date?->format('Y-m-d\TH:i') }}" required class="input-boutique">
        </div>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-ink-700">Giới hạn lượt dùng</label>
        <input type="number" name="usage_limit" value="{{ $coupon->usage_limit }}" min="0" class="input-boutique">
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-ink-700">Trạng thái</label>
        <select name="status" class="input-boutique">
            <option value="1" @selected((int) $coupon->status === 1)>Đang hoạt động</option>
            <option value="0" @selected((int) $coupon->status === 0)>Tắt</option>
        </select>
    </div>
    <button type="submit" class="btn-boutique rounded-md px-5 py-2.5 text-sm">Lưu</button>
</form>
@endsection
