@php
    $navItems = [
        ['route' => 'admin.dashboard', 'match' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'fa-gauge'],
        ['route' => 'admin.categories.index', 'match' => 'admin.categories.*', 'label' => 'Danh mục', 'icon' => 'fa-tags'],
        ['route' => 'admin.products.index', 'match' => 'admin.products.*', 'label' => 'Sản phẩm', 'icon' => 'fa-laptop'],
        ['route' => 'admin.orders.index', 'match' => 'admin.orders.*', 'label' => 'Đơn hàng', 'icon' => 'fa-receipt'],
        ['route' => 'admin.users.index', 'match' => 'admin.users.*', 'label' => 'Người dùng', 'icon' => 'fa-users'],
        ['route' => 'admin.coupons.index', 'match' => 'admin.coupons.*', 'label' => 'Mã giảm giá', 'icon' => 'fa-ticket'],
        ['route' => 'admin.comments.index', 'match' => 'admin.comments.*', 'label' => 'Bình luận', 'icon' => 'fa-comments'],
        ['route' => 'admin.questions.index', 'match' => 'admin.questions.*', 'label' => 'Hỏi đáp', 'icon' => 'fa-circle-question'],
        ['route' => 'admin.stats.index', 'match' => 'admin.stats.*', 'label' => 'Thống kê', 'icon' => 'fa-chart-line'],
    ];
@endphp

<aside class="w-60 shrink-0 border-r border-ink-300 bg-white">
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 border-b border-ink-200 px-5 py-5">
        <span class="font-heading text-lg font-bold text-ink-900">Turbo<span class="text-brand-600">tech</span></span>
    </a>
    <nav class="space-y-0.5 p-3 text-sm">
        @foreach ($navItems as $item)
            <a href="{{ route($item['route']) }}"
                class="sidebar-link rounded-md px-3 py-2.5 text-ink-700 hover:bg-ink-100 hover:text-brand-600 {{ request()->routeIs($item['match']) ? 'is-active' : '' }}">
                <i class="fas {{ $item['icon'] }} w-4 text-center text-xs"></i>
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>
</aside>
