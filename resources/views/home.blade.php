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

{{-- Sản phẩm mới — the main browse grid, every item the controller sends. --}}
<section class="reveal-on-scroll mb-16">
    <div class="mb-6 flex items-end justify-between border-b border-ink-200 pb-4">
        <div>
            <span class="eyebrow">Tuyển chọn</span>
            <h2 class="mt-2 font-heading text-2xl font-semibold text-ink-900">Sản phẩm mới</h2>
        </div>
        <a href="{{ route('product.index') }}" class="hidden text-sm font-medium text-brand-600 hover:underline sm:block">Xem tất cả &rarr;</a>
    </div>
    <div class="grid grid-cols-2 gap-5 sm:grid-cols-3 lg:grid-cols-4">
        @foreach ($prohome as $product)
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

{{-- Bán chạy nhất — a horizontal scroll strip instead of a wrapped grid, so it
     doesn't read as the exact same layout as the section above. --}}
<section class="reveal-on-scroll mb-16">
    <div class="mb-6 flex items-end justify-between border-b border-ink-200 pb-4">
        <div>
            <span class="eyebrow">Tuyển chọn</span>
            <h2 class="mt-2 font-heading text-2xl font-semibold text-ink-900">Bán chạy nhất</h2>
        </div>
        <a href="{{ route('product.index') }}" class="hidden text-sm font-medium text-brand-600 hover:underline sm:block">Xem tất cả &rarr;</a>
    </div>
    <div class="scroll-strip -mx-4 flex gap-5 overflow-x-auto px-4 pb-3 sm:mx-0 sm:px-0">
        @foreach ($listBestsp as $product)
            <a href="{{ route('product.show', $product->id_pro) }}" class="card-boutique flex w-44 shrink-0 flex-col overflow-hidden rounded-md sm:w-56">
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

{{-- Nổi bật — a spotlight layout (fewer, larger cards with a description
     snippet) instead of another dense grid. --}}
<section class="reveal-on-scroll mb-16">
    <div class="mb-6 flex items-end justify-between border-b border-ink-200 pb-4">
        <div>
            <span class="eyebrow">Tuyển chọn</span>
            <h2 class="mt-2 font-heading text-2xl font-semibold text-ink-900">Nổi bật</h2>
        </div>
        <a href="{{ route('product.index') }}" class="hidden text-sm font-medium text-brand-600 hover:underline sm:block">Xem tất cả &rarr;</a>
    </div>
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        @foreach ($listTopsp->take(4) as $product)
            <a href="{{ route('product.show', $product->id_pro) }}" class="card-boutique group flex gap-5 overflow-hidden rounded-md p-4">
                <div class="relative aspect-square w-28 shrink-0 overflow-hidden rounded-md bg-ink-100 sm:w-36">
                    <img src="{{ asset('storage/products/'.$product->img_pro) }}" alt="{{ $product->name_pro }}" loading="lazy" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                    @if ($product->discount > 0)
                        <span class="absolute left-1.5 top-1.5 rounded-sm bg-accent-600 px-1.5 py-0.5 text-[10px] font-bold text-white">-{{ $product->discount }}%</span>
                    @endif
                </div>
                <div class="flex min-w-0 flex-1 flex-col">
                    <h3 class="font-heading text-base font-semibold text-ink-900">{{ $product->name_pro }}</h3>
                    <p class="mt-1 line-clamp-2 text-xs text-ink-500">{{ $product->short_des }}</p>
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
@endsection
