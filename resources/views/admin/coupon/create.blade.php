@extends('admin.layouts.app')

@section('page-title', 'Thêm mã giảm giá')

@section('content')
<form action="{{ route('admin.coupons.store') }}" method="post" class="max-w-xl space-y-4 rounded-md border border-ink-300 bg-white p-6 shadow-sm">
    @csrf
    <div>
        <label class="mb-1 block text-sm font-medium text-ink-700">Mã</label>
        <input type="text" name="code" required class="input-boutique">
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Loại giảm giá</label>
            <select name="discount_type" class="input-boutique">
                <option value="1">Phần trăm (%)</option>
                <option value="2">Số tiền cố định (VNĐ)</option>
            </select>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Giá trị</label>
            <input type="number" name="discount_value" required min="1" class="input-boutique">
        </div>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Giảm tối đa (0 = không giới hạn)</label>
            <input type="number" name="max_discount" value="0" min="0" class="input-boutique">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Đơn tối thiểu</label>
            <input type="number" name="min_order_value" value="0" min="0" class="input-boutique">
        </div>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-ink-700">Áp dụng cho sản phẩm (để trống = mọi sản phẩm)</label>
        <select name="product_id" class="input-boutique">
            <option value="0">Tất cả sản phẩm</option>
            @foreach ($products as $product)
                <option value="{{ $product->id_pro }}">{{ $product->name_pro }}</option>
            @endforeach
        </select>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Ngày bắt đầu</label>
            <input type="datetime-local" name="start_date" required class="input-boutique">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Ngày kết thúc</label>
            <input type="datetime-local" name="end_date" required class="input-boutique">
        </div>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-ink-700">Giới hạn lượt dùng (0 = không giới hạn)</label>
        <input type="number" name="usage_limit" value="0" min="0" class="input-boutique">
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-ink-700">Trạng thái</label>
        <select name="status" class="input-boutique">
            <option value="1">Đang hoạt động</option>
            <option value="0">Tắt</option>
        </select>
    </div>
    <button type="submit" class="btn-boutique rounded-md px-5 py-2.5 text-sm">Lưu</button>
</form>
@endsection
