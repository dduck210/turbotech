@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 gap-10 lg:grid-cols-2">
    <div class="aspect-square overflow-hidden rounded-md border border-ink-200 bg-ink-100">
        <img src="{{ asset('admin/uploads/'.$product->img_pro) }}" alt="{{ $product->name_pro }}" class="h-full w-full object-cover">
    </div>

    <div>
        <h1 class="font-heading text-2xl font-semibold text-ink-900">{{ $product->name_pro }}</h1>
        <div class="mt-4 flex items-baseline gap-3">
            @if ($product->discount > 0)
                <span class="text-lg text-ink-400 line-through">{{ number_format($product->price) }}₫</span>
                <span class="rounded-sm bg-accent-600 px-2 py-1 text-xs font-bold text-white">-{{ $product->discount }}%</span>
            @endif
            <span class="font-heading text-3xl font-bold text-brand-600">{{ number_format($product->discounted_price) }}₫</span>
        </div>

        <p class="mt-4 text-sm text-ink-600">
            {{ $product->stock > 0 ? 'Còn hàng ('.$product->stock.' sản phẩm)' : ($product->stock_message ?: 'Hết hàng') }}
        </p>

        <form action="{{ route('cart.add') }}" method="post" class="mt-6 flex items-center gap-3">
            @csrf
            <input type="hidden" name="id_pro" value="{{ $product->id_pro }}">
            <input type="number" name="quatity" value="1" min="1" max="{{ $product->stock }}" class="w-20 rounded-md border border-ink-300 px-3 py-2 text-sm">
            <button type="submit" name="addtocart" value="1" @disabled($product->stock <= 0)
                class="btn-boutique rounded-md px-6 py-2.5 text-sm font-semibold disabled:opacity-50">
                {{ $product->stock > 0 ? 'Thêm vào giỏ' : 'Hết hàng' }}
            </button>
        </form>

        <div class="mt-8 border-t border-ink-200 pt-6 text-sm text-ink-700">
            {{ $product->short_des }}
        </div>
        <div class="prose prose-sm mt-4 max-w-none text-ink-700">
            {!! $product->detail_des !!}
        </div>
    </div>
</div>

<section class="mt-14">
    <h2 class="mb-4 font-heading text-xl font-semibold text-ink-900">Sản phẩm tương tự</h2>
    <div class="grid grid-cols-2 gap-5 sm:grid-cols-3 lg:grid-cols-5">
        @foreach ($similar as $sp)
            <a href="{{ route('product.show', $sp->id_pro) }}" class="card-boutique flex flex-col overflow-hidden rounded-md">
                <div class="aspect-square overflow-hidden bg-ink-100">
                    <img src="{{ asset('admin/uploads/'.$sp->img_pro) }}" alt="{{ $sp->name_pro }}" loading="lazy" class="h-full w-full object-cover">
                </div>
                <div class="p-3">
                    <h3 class="line-clamp-2 text-sm font-medium text-ink-900">{{ $sp->name_pro }}</h3>
                    <div class="mt-1 font-semibold text-brand-600">{{ number_format($sp->discounted_price) }}₫</div>
                </div>
            </a>
        @endforeach
    </div>
</section>

<section class="mt-14 max-w-2xl">
    <h2 class="mb-4 font-heading text-xl font-semibold text-ink-900">Đánh giá sản phẩm</h2>
    <div class="space-y-3">
        @forelse ($comments as $cmt)
            <div class="border-b border-ink-200 pb-3">
                <div class="flex flex-wrap items-baseline gap-2">
                    <span class="font-heading font-semibold text-ink-900">{{ $cmt->full_name }} ({{ $cmt->user_name }})</span>
                    <em class="text-xs text-ink-500 not-italic">{{ $cmt->comment_date }}</em>
                </div>
                <p class="mt-1.5 text-sm text-ink-700">{{ $cmt->content }}</p>
            </div>
        @empty
            <p class="text-sm text-ink-500">Chưa có đánh giá nào cho sản phẩm này.</p>
        @endforelse
    </div>

    <div class="mt-4">
        @auth
            @if ($canReview)
                <form action="{{ route('product.reviews.store', $product->id_pro) }}" method="post" class="card-boutique rounded-md p-4">
                    @csrf
                    <label class="mb-1.5 block text-sm font-medium text-ink-700">Bình luận của bạn</label>
                    <textarea name="content_cmt" rows="3" required minlength="2" class="w-full rounded-md border border-ink-300 px-3.5 py-2.5 text-sm"></textarea>
                    <button type="submit" class="btn-boutique mt-3 rounded-md px-5 py-2.5 text-sm font-semibold">Gửi</button>
                </form>
            @elseif ($alreadyReviewed)
                <p class="rounded-md border border-emerald-300 bg-emerald-50 p-3 text-sm text-emerald-800">Bạn đã đánh giá sản phẩm này rồi. Cảm ơn bạn!</p>
            @else
                <p class="rounded-md border border-amber-300 bg-amber-50 p-3 text-sm text-amber-800">Bạn cần mua và nhận sản phẩm này để có thể đánh giá.</p>
            @endif
        @else
            <p class="rounded-md border border-brand-300 bg-brand-50 p-3 text-sm text-brand-700">Vui lòng đăng nhập để bình luận !</p>
        @endauth
    </div>
</section>
@endsection
