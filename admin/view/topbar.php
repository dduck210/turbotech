<header
    class="h-20 bg-ink-50 border-b border-ink-300 flex items-center justify-between px-6 shrink-0 z-10 sticky top-0">
    <div class="flex items-center gap-4">
        <button id="mobile-sidebar-toggle" class="md:hidden text-ink-500 hover:text-brand-600 transition-colors">
            <i class="fas fa-bars text-xl"></i>
        </button>
        <!-- Search -->
        <div class="hidden md:flex items-center relative border border-ink-300 rounded-md focus-within:border-brand-500 transition-colors">
            <i class="fas fa-search absolute left-3 text-ink-400 text-sm"></i>
            <input type="text" placeholder="Tìm kiếm..."
                class="bg-transparent text-sm pl-9 pr-4 py-2 border-none outline-none w-40 lg:w-64 transition-all focus:w-56 lg:focus:w-80 text-ink-800 placeholder:text-ink-400">
        </div>
    </div>

    <div class="flex items-center gap-4">
        <!-- Notifications -->
        <button
            class="relative p-2 text-ink-400 hover:text-brand-600 transition-colors rounded-md hover:bg-ink-200">
            <i class="fas fa-bell text-lg"></i>
            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-accent-500 rounded-full border border-ink-50"></span>
        </button>

        <!-- Profile -->
        <div class="flex items-center gap-3 border border-ink-300 rounded-md pl-3 pr-2 py-1.5">
            <div class="text-right hidden lg:block whitespace-nowrap">
                <div class="text-sm font-semibold text-ink-800">Admin Turbotech</div>
                <div class="text-xs text-ink-500">Quản trị viên</div>
            </div>
            <img src="../assets/admin-images/admin.png"
                onerror="this.src='https://ui-avatars.com/api/?name=Admin&background=f3e2c5&color=7a5c32'"
                class="w-9 h-9 rounded-full border border-ink-300 object-cover">
            <a href="index.php?act=logout" data-confirm="Bạn có chắc chắn muốn đăng xuất?"
                class="p-1.5 text-ink-400 hover:text-red-600 transition-colors rounded-md hover:bg-red-50"
                title="Đăng xuất">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </div>
</header>
