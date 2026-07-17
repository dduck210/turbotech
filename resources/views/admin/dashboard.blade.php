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
@endsection
