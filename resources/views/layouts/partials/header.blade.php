@php($cartCount = app(\App\Services\CartService::class)->count())

<div class="border-b border-ink-300 bg-ink-950 py-2 text-center text-xs tracking-wide text-ink-200">
    Miễn phí vận chuyển toàn quốc &middot; Bảo hành chính hãng 12 tháng
</div>

<header class="sticky top-0 z-30 border-b border-ink-200 bg-white/95 backdrop-blur">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-4 py-4 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" class="shrink-0 font-heading text-2xl font-bold tracking-tight text-ink-900">
            Turbo<span class="text-brand-600">tech</span>
        </a>

        <nav class="hidden items-center gap-7 text-sm font-medium text-ink-700 md:flex">
            <a href="{{ route('product.index') }}" class="border-b-2 border-transparent pb-1 transition-colors hover:border-brand-500 hover:text-brand-600">Sản phẩm</a>
            <a href="{{ route('introduce') }}" class="border-b-2 border-transparent pb-1 transition-colors hover:border-brand-500 hover:text-brand-600">Giới thiệu</a>
            <a href="{{ route('contact') }}" class="border-b-2 border-transparent pb-1 transition-colors hover:border-brand-500 hover:text-brand-600">Liên hệ</a>
            <a href="{{ route('question.index') }}" class="border-b-2 border-transparent pb-1 transition-colors hover:border-brand-500 hover:text-brand-600">Hỏi đáp</a>
        </nav>

        <div class="flex items-center gap-4 text-sm font-medium text-ink-700">
            <a href="{{ route('cart.view') }}" class="relative flex h-10 w-10 items-center justify-center rounded-full transition-colors hover:bg-ink-100 hover:text-brand-600" aria-label="Giỏ hàng">
                <i class="fas fa-shopping-bag text-lg"></i>
                @if ($cartCount > 0)
                    <span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-accent-600 px-1 text-[10px] font-bold leading-none text-white">{{ $cartCount }}</span>
                @endif
            </a>

            @auth
                <div class="hidden items-center gap-4 sm:flex">
                    <a href="{{ route('account.index') }}" class="hover:text-brand-600">{{ auth()->user()->full_name }}</a>
                    @if ((int) auth()->user()->role === 1)
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-600">Trang Admin</a>
                    @endif
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <button type="submit" class="hover:text-brand-600">Đăng xuất</button>
                    </form>
                </div>
            @else
                <div class="hidden items-center gap-3 sm:flex">
                    <a href="{{ route('login.show') }}" class="hover:text-brand-600">Đăng nhập</a>
                    <a href="{{ route('register.show') }}" class="btn-boutique rounded-md px-4 py-2 text-xs">Đăng ký</a>
                </div>
            @endauth
        </div>
    </div>
</header>
