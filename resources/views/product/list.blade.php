@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 gap-8 lg:grid-cols-[240px_1fr]">
    <aside class="h-fit space-y-6 rounded-md border border-ink-200 bg-white p-5">
        <div>
            <h3 class="eyebrow mb-3">Danh mục</h3>
            <ul class="space-y-1.5 text-sm">
                <li><a href="{{ route('product.index') }}" class="text-ink-700 transition-colors hover:text-brand-600">Tất cả</a></li>
                @foreach ($listcate as $cate)
                    <li><a href="{{ route('product.index', ['idcate' => $cate->id_cate]) }}" class="text-ink-700 transition-colors hover:text-brand-600">{{ $cate->name_cate }}</a></li>
                @endforeach
            </ul>
        </div>
        <form action="{{ route('product.index') }}" method="get" class="space-y-2 border-t border-ink-200 pt-5">
            <h3 class="eyebrow mb-3">Khoảng giá</h3>
            <input type="number" name="min_price" placeholder="Từ" value="{{ request('min_price') }}" class="input-boutique">
            <input type="number" name="max_price" placeholder="Đến" value="{{ request('max_price') }}" class="input-boutique">
            <button type="submit" class="btn-boutique w-full rounded-md px-4 py-2.5 text-sm">Lọc</button>
        </form>
    </aside>

    <div>
        <div class="mb-6 border-b border-ink-200 pb-4">
            <span class="eyebrow">Bộ sưu tập</span>
            <h1 class="mt-2 font-heading text-2xl font-semibold text-ink-900">
                {{ $namecate !== '' ? 'Laptop '.$namecate : 'Tất cả sản phẩm' }}
            </h1>
        </div>
        <div class="grid grid-cols-2 gap-5 sm:grid-cols-3">
            @forelse ($listpro as $product)
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
            @empty
                <p class="col-span-full py-10 text-center text-ink-500">Không tìm thấy sản phẩm nào.</p>
            @endforelse
        </div>
        <div class="mt-8">
            {{ $listpro->links() }}
        </div>
    </div>
</div>
@endsection
