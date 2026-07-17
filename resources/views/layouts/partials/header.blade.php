<div class="border-b border-ink-950 bg-ink-950 py-2 text-center text-xs tracking-wide text-ink-200">
    Miễn phí vận chuyển toàn quốc &middot; Bảo hành chính hãng 12 tháng
</div>

<header class="sticky top-0 z-40 border-b border-ink-300 bg-ink-50">
    <div class="mx-auto max-w-7xl px-3 sm:px-6 lg:px-8">
        <div class="flex h-20 items-center justify-between gap-2 sm:gap-4">
            <a href="{{ route('home') }}" class="flex shrink-0 items-center rounded-md focus:outline-none focus:ring-2 focus:ring-brand-500">
                <img src="{{ asset('assets/images/menu/logo/logo-wordmark-light.svg') }}" alt="Turbotech" class="h-8 w-auto sm:h-9">
            </a>

            <nav class="hidden items-center gap-6 lg:flex">
                <a href="{{ route('home') }}" class="relative py-2 text-sm font-medium uppercase tracking-wide text-ink-700 transition-colors after:absolute after:-bottom-0.5 after:left-0 after:h-px after:w-0 after:bg-brand-500 after:transition-all hover:text-brand-600 hover:after:w-full">Trang chủ</a>

                <div class="group relative">
                    <a href="{{ route('product.index') }}" class="inline-flex items-center gap-1.5 py-2 text-sm font-medium uppercase tracking-wide text-ink-700 transition-colors hover:text-brand-600">
                        Sản phẩm <i class="fa-solid fa-chevron-down text-[10px]"></i>
                    </a>
                    <div class="card-boutique invisible absolute left-0 top-full z-50 w-56 -translate-y-1 rounded-md py-2 opacity-0 transition-all duration-200 ease-out group-hover:visible group-hover:translate-y-0 group-hover:opacity-100">
                        @foreach ($headerCategories as $cate)
                            <a href="{{ route('product.index', ['idcate' => $cate->id_cate]) }}" class="block px-4 py-2 text-sm text-ink-700 hover:bg-ink-100 hover:text-brand-600">{{ $cate->name_cate }}</a>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('introduce') }}" class="relative py-2 text-sm font-medium uppercase tracking-wide text-ink-700 transition-colors after:absolute after:-bottom-0.5 after:left-0 after:h-px after:w-0 after:bg-brand-500 after:transition-all hover:text-brand-600 hover:after:w-full">Giới thiệu</a>
                <a href="{{ route('contact') }}" class="relative py-2 text-sm font-medium uppercase tracking-wide text-ink-700 transition-colors after:absolute after:-bottom-0.5 after:left-0 after:h-px after:w-0 after:bg-brand-500 after:transition-all hover:text-brand-600 hover:after:w-full">Liên hệ</a>
                <a href="{{ route('question.index') }}" class="relative py-2 text-sm font-medium uppercase tracking-wide text-ink-700 transition-colors after:absolute after:-bottom-0.5 after:left-0 after:h-px after:w-0 after:bg-brand-500 after:transition-all hover:text-brand-600 hover:after:w-full">Hỏi đáp</a>
            </nav>

            <form action="{{ route('product.index') }}" method="get" class="hidden max-w-xs flex-1 md:flex">
                <label for="header-search" class="sr-only">Tìm kiếm sản phẩm</label>
                <div class="relative w-full border-b border-ink-300 focus-within:border-brand-500">
                    <input id="header-search" name="kyw" type="text" placeholder="Tìm kiếm sản phẩm ..." class="block w-full bg-transparent py-2 pl-1 pr-9 text-sm text-ink-900 placeholder:text-ink-500 focus:outline-none">
                    <button type="submit" aria-label="Tìm kiếm" class="absolute right-0 top-1/2 flex h-8 w-8 -translate-y-1/2 items-center justify-center text-ink-500 transition-colors hover:text-brand-600">
                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    </button>
                </div>
            </form>

            <div class="flex shrink-0 items-center gap-0.5 sm:gap-1">
                <div class="group relative">
                    <a href="{{ route('cart.view') }}" aria-label="Giỏ hàng" class="relative flex h-11 w-11 items-center justify-center rounded-full text-ink-700 transition-colors hover:bg-ink-100">
                        <i class="fa-solid fa-cart-shopping text-lg"></i>
                        @if ($headerCartCount > 0)
                            <span class="absolute right-0.5 top-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full bg-brand-600 text-[11px] font-bold text-white">{{ $headerCartCount }}</span>
                        @endif
                    </a>
                    <div class="card-boutique invisible absolute right-0 top-full z-50 w-80 max-w-[calc(100vw-1.5rem)] -translate-y-1 rounded-md opacity-0 transition-all duration-200 ease-out group-hover:visible group-hover:translate-y-0 group-hover:opacity-100">
                        @if (empty($headerCartItems))
                            <p class="p-6 text-center text-sm text-ink-500">Bạn chưa thêm sản phẩm nào vào giỏ hàng !</p>
                        @else
                            <ul class="max-h-80 divide-y divide-ink-100 overflow-y-auto">
                                @foreach ($headerCartItems as $line)
                                    <li class="flex gap-3 p-3">
                                        <img src="{{ asset('storage/products/'.$line['img_pro']) }}" class="h-14 w-14 shrink-0 rounded-md border border-ink-200 object-cover">
                                        <div class="min-w-0 flex-1">
                                            <p class="truncate text-sm font-medium text-ink-900">{{ $line['name_pro'] }}</p>
                                            <p class="text-xs text-ink-500">{{ $line['quantity'] }} &times; {{ number_format($line['price']) }}₫</p>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="flex items-center justify-between border-t border-ink-200 p-3">
                                <span class="text-sm font-semibold text-ink-900">Tổng: <span class="price">{{ number_format($headerCartTotal) }}₫</span></span>
                                <a href="{{ route('cart.view') }}" class="btn-boutique rounded-md px-3 py-1.5 text-xs">Xem giỏ hàng</a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="group relative hidden sm:block">
                    @auth
                        <a href="{{ route('account.index') }}" class="flex h-11 items-center gap-2 rounded-full px-3 text-sm font-medium text-ink-700 transition-colors hover:bg-ink-100">
                            @if (auth()->user()->img_user)
                                <img src="{{ asset('storage/avatars/'.auth()->user()->img_user) }}" alt="" class="h-7 w-7 rounded-full object-cover">
                            @else
                                <i class="fa-solid fa-user text-lg"></i>
                            @endif
                            <span class="hidden max-w-48 truncate sm:inline">{{ auth()->user()->full_name }}</span>
                        </a>
                    @else
                        <a href="{{ route('login.show') }}" class="flex h-11 items-center gap-2 rounded-full px-3 text-sm font-medium text-ink-700 transition-colors hover:bg-ink-100">
                            <i class="fa-solid fa-user text-lg"></i> <span class="hidden sm:inline">Tài khoản</span>
                        </a>
                    @endauth
                    <div class="card-boutique invisible absolute right-0 top-full z-50 w-56 -translate-y-1 rounded-md py-2 opacity-0 transition-all duration-200 ease-out group-hover:visible group-hover:translate-y-0 group-hover:opacity-100">
                        @auth
                            @if ((int) auth()->user()->role === 1)
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-ink-700 hover:bg-ink-50 hover:text-brand-600"><i class="fa-solid fa-gears w-4"></i> Vào trang Admin</a>
                            @endif
                            <a href="{{ route('account.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-ink-700 hover:bg-ink-50 hover:text-brand-600"><i class="fa-solid fa-circle-info w-4"></i> Thông tin tài khoản</a>
                            <form action="{{ route('logout') }}" method="post">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm text-ink-700 hover:bg-ink-50 hover:text-brand-600"><i class="fa-solid fa-right-from-bracket w-4"></i> Đăng xuất</button>
                            </form>
                        @else
                            <a href="{{ route('login.show') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-ink-700 hover:bg-ink-50 hover:text-brand-600"><i class="fa-solid fa-right-to-bracket w-4"></i> Đăng nhập</a>
                            <a href="{{ route('register.show') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-ink-700 hover:bg-ink-50 hover:text-brand-600"><i class="fa-solid fa-user-plus w-4"></i> Đăng ký</a>
                        @endauth
                    </div>
                </div>

                <button type="button" id="mobile-menu-btn" aria-label="Mở menu" aria-expanded="false" aria-controls="mobile-menu" class="flex h-11 w-11 items-center justify-center rounded-full text-ink-700 transition-colors hover:bg-ink-100 lg:hidden">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
            </div>
        </div>

        <form action="{{ route('product.index') }}" method="get" class="pb-3 md:hidden">
            <label for="header-search-mobile" class="sr-only">Tìm kiếm sản phẩm</label>
            <div class="relative w-full border-b border-ink-300 focus-within:border-brand-500">
                <input id="header-search-mobile" name="kyw" type="text" placeholder="Tìm kiếm sản phẩm ..." class="block w-full bg-transparent py-2.5 pl-1 pr-9 text-sm text-ink-900 placeholder:text-ink-500 focus:outline-none">
                <button type="submit" aria-label="Tìm kiếm" class="absolute right-0 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center text-ink-500 transition-colors hover:text-brand-600">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </form>
    </div>

    <nav id="mobile-menu" class="grid grid-rows-[0fr] overflow-hidden border-t border-ink-300 bg-ink-50 transition-[grid-template-rows] duration-300 ease-in-out lg:hidden">
        <div class="min-h-0 overflow-hidden">
            <div class="mx-auto max-w-7xl space-y-1 px-4 py-4 sm:px-6">
                <a href="{{ route('home') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-ink-700 hover:bg-ink-100">Trang chủ</a>
                <details class="rounded-lg">
                    <summary class="flex cursor-pointer list-none items-center justify-between rounded-lg px-3 py-2.5 text-sm font-medium text-ink-700 hover:bg-ink-100">
                        <span>Sản phẩm</span> <i class="fa-solid fa-chevron-down text-xs"></i>
                    </summary>
                    <div class="ml-3 mt-1 space-y-1 border-l border-ink-200 pl-3">
                        @foreach ($headerCategories as $cate)
                            <a href="{{ route('product.index', ['idcate' => $cate->id_cate]) }}" class="block rounded-lg px-3 py-2 text-sm text-ink-700 hover:bg-ink-100 hover:text-brand-600">{{ $cate->name_cate }}</a>
                        @endforeach
                    </div>
                </details>
                <a href="{{ route('introduce') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-ink-700 hover:bg-ink-100">Giới thiệu</a>
                <a href="{{ route('contact') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-ink-700 hover:bg-ink-100">Liên hệ</a>
                <a href="{{ route('question.index') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-ink-700 hover:bg-ink-100">Hỏi đáp</a>

                <div class="mt-2 space-y-1 border-t border-ink-200 pt-2 sm:hidden">
                    @auth
                        @if ((int) auth()->user()->role === 1)
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-medium text-ink-700 hover:bg-ink-100"><i class="fa-solid fa-gears w-4"></i> Vào trang Admin</a>
                        @endif
                        <a href="{{ route('account.index') }}" class="flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-medium text-ink-700 hover:bg-ink-100"><i class="fa-solid fa-circle-info w-4"></i> {{ auth()->user()->full_name }}</a>
                        <form action="{{ route('logout') }}" method="post">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-3 py-2.5 text-left text-sm font-medium text-ink-700 hover:bg-ink-100"><i class="fa-solid fa-right-from-bracket w-4"></i> Đăng xuất</button>
                        </form>
                    @else
                        <a href="{{ route('login.show') }}" class="flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-medium text-ink-700 hover:bg-ink-100"><i class="fa-solid fa-right-to-bracket w-4"></i> Đăng nhập</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
</header>

<script>
    (function () {
        var btn = document.getElementById('mobile-menu-btn');
        var menu = document.getElementById('mobile-menu');
        if (!btn || !menu) return;
        btn.addEventListener('click', function () {
            var isNowOpen = menu.classList.toggle('grid-rows-[1fr]');
            menu.classList.toggle('grid-rows-[0fr]', !isNowOpen);
            btn.setAttribute('aria-expanded', String(isNowOpen));
        });
    })();
</script>
