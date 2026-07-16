@extends('admin.layouts.app')

@section('content')
<h1 class="mb-6 font-heading text-2xl font-semibold text-ink-900">Dashboard</h1>
<div class="grid grid-cols-2 gap-4 md:grid-cols-5">
    @foreach ([['Người dùng', $userCount], ['Sản phẩm', $productCount], ['Danh mục', $categoryCount], ['Đơn hàng', $orderCount], ['Bình luận', $commentCount]] as [$label, $value])
        <div class="rounded-md border border-ink-300 bg-white p-4">
            <div class="text-2xl font-bold text-brand-600">{{ $value }}</div>
            <div class="text-sm text-ink-500">{{ $label }}</div>
        </div>
    @endforeach
</div>
<p class="mt-6 text-sm text-ink-500">Biểu đồ doanh thu/thống kê chi tiết — xem trang Thống kê.</p>
@endsection
