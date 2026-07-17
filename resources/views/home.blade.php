@extends('layouts.app')

@section('content')
<section class="mb-12 rounded-lg bg-gradient-to-r from-brand-600 to-brand-700 px-8 py-16 text-white">
    <h1 class="font-heading text-4xl font-bold">Nâng tầm trải nghiệm công nghệ của bạn</h1>
    <p class="mt-3 max-w-xl text-brand-50">Laptop gaming &amp; PC hiệu năng cao chính hãng, giá tốt nhất thị trường.</p>
    <a href="{{ route('product.index') }}" class="mt-6 inline-block rounded-md bg-white px-6 py-3 text-sm font-semibold text-brand-700">Xem sản phẩm</a>
</section>

@php($cards = [['Sản phẩm mới', $prohome], ['Bán chạy', $listBestsp], ['Nổi bật', $listTopsp]])

@foreach ($cards as [$heading, $products])
    <section class="mb-14">
        <h2 class="mb-5 font-heading text-2xl font-semibold text-ink-900">{{ $heading }}</h2>
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
                            <div class="font-semibold text-brand-600">{{ number_format($product->discounted_price) }}₫</div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
@endforeach
@endsection
