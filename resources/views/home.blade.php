@extends('layouts.app')

@section('content')
<section class="hero-texture relative mb-16 overflow-hidden rounded-lg bg-gradient-to-br from-ink-950 via-ink-900 to-brand-900 px-8 py-20 text-white sm:px-14">
    <div class="relative max-w-2xl">
        <span class="eyebrow text-brand-400">Turbotech &middot; Chính hãng &middot; Bảo hành 12 tháng</span>
        <h1 class="mt-5 font-heading text-4xl font-bold leading-tight sm:text-5xl">Nâng tầm trải nghiệm<br>công nghệ của bạn</h1>
        <p class="mt-5 max-w-lg text-base text-ink-200">Laptop gaming &amp; PC hiệu năng cao chính hãng — cấu hình mạnh mẽ, giá tốt nhất thị trường, giao hàng toàn quốc.</p>
        <div class="mt-8 flex flex-wrap gap-4">
            <a href="{{ route('product.index') }}" class="btn-boutique rounded-md px-7 py-3 text-sm">Khám phá sản phẩm</a>
            <a href="{{ route('introduce') }}" class="btn-boutique-outline rounded-md px-7 py-3 text-sm text-white"><span>Tìm hiểu thêm</span></a>
        </div>
    </div>
</section>

@php($cards = [['Sản phẩm mới', $prohome], ['Bán chạy nhất', $listBestsp], ['Nổi bật', $listTopsp]])

@foreach ($cards as [$heading, $products])
    <section class="reveal-on-scroll mb-16">
        <div class="mb-6 flex items-end justify-between border-b border-ink-200 pb-4">
            <div>
                <span class="eyebrow">Tuyển chọn</span>
                <h2 class="mt-2 font-heading text-2xl font-semibold text-ink-900">{{ $heading }}</h2>
            </div>
            <a href="{{ route('product.index') }}" class="hidden text-sm font-medium text-brand-600 hover:underline sm:block">Xem tất cả &rarr;</a>
        </div>
        <div class="grid grid-cols-2 gap-5 sm:grid-cols-3 lg:grid-cols-4">
            @foreach ($products as $product)
                <a href="{{ route('product.show', $product->id_pro) }}" class="card-boutique flex flex-col overflow-hidden rounded-md">
                    <div class="relative aspect-square overflow-hidden bg-ink-100">
                        <img src="{{ asset('storage/products/'.$product->img_pro) }}" alt="{{ $product->name_pro }}" loading="lazy" class="h-full w-full object-cover">
                        @if ($product->discount > 0)
                            <span class="absolute left-2 top-2 rounded-sm bg-accent-600 px-2 py-1 text-[11px] font-bold text-white">-{{ $product->discount }}%</span>
                        @endif
                    </div>
                    <div class="flex flex-1 flex-col p-4">
                        <h3 class="line-clamp-2 text-sm font-medium text-ink-900">{{ $product->name_pro }}</h3>
                        <div class="mt-auto pt-2">
                            @if ($product->discount > 0)
                                <span class="text-xs text-ink-400 line-through">{{ number_format($product->price) }}₫</span>
                            @endif
                            <div class="price text-lg font-semibold">{{ number_format($product->discounted_price) }}₫</div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
@endforeach
@endsection
