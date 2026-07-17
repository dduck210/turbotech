<header class="flex items-center justify-between border-b border-ink-300 bg-white px-6 py-4">
    <h1 class="font-heading text-lg font-semibold text-ink-900">@yield('page-title')</h1>
    <div class="flex items-center gap-4 text-sm">
        <a href="{{ route('home') }}" class="text-ink-600 transition-colors hover:text-brand-600"><i class="fas fa-arrow-up-right-from-square mr-1"></i>Xem trang web</a>
        <div class="flex items-center gap-2.5 border-l border-ink-200 pl-4">
            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-brand-100 text-xs font-bold text-brand-700">
                {{ mb_substr(auth()->user()->full_name, 0, 1) }}
            </span>
            <span class="text-ink-700">{{ auth()->user()->full_name }}</span>
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button type="submit" class="text-ink-400 transition-colors hover:text-red-600" title="Đăng xuất"><i class="fas fa-arrow-right-from-bracket"></i></button>
            </form>
        </div>
    </div>
</header>
