<header
    class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200 flex items-center justify-between px-6 shrink-0 z-10 sticky top-0 shadow-sm">
    <div class="flex items-center">
        <button id="mobile-sidebar-toggle" class="md:hidden text-slate-500 hover:text-brand-600 transition-colors">
            <i class="fas fa-bars text-xl"></i>
        </button>
        <!-- Search -->
        <div class="hidden md:flex relative ml-4">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input type="text" placeholder="Tìm kiếm..."
                class="bg-slate-100 text-sm rounded-full pl-10 pr-4 py-2 border-none focus:ring-2 focus:ring-brand-500 outline-none w-40 lg:w-64 transition-all focus:w-56 lg:focus:w-80">
        </div>
    </div>

    <div class="flex items-center space-x-4">
        <!-- Notifications -->
        <button
            class="relative p-2 text-slate-400 hover:text-brand-600 transition-colors rounded-full hover:bg-brand-50">
            <i class="fas fa-bell text-lg"></i>
            <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
        </button>

        <!-- Profile Dropdown -->
        <div class="flex items-center space-x-3 border-l border-slate-200 pl-4 ml-2">
            <div class="text-right hidden lg:block whitespace-nowrap">
                <div class="text-sm font-semibold text-slate-700">Admin Turbotech</div>
                <div class="text-xs text-slate-500 font-medium">Quản trị viên</div>
            </div>
            <img src="../assets/admin-images/admin.png"
                onerror="this.src='https://ui-avatars.com/api/?name=Admin&background=eff6ff&color=2563eb'"
                class="w-10 h-10 rounded-full border-2 border-brand-100 object-cover shadow-sm">
            <a href="index.php?act=logout" data-confirm="Bạn có chắc chắn muốn đăng xuất?"
                class="ml-2 p-2 text-slate-400 hover:text-red-500 transition-colors rounded-full hover:bg-red-50"
                title="Đăng xuất">
                <i class="fas fa-sign-out-alt text-lg"></i>
            </a>
        </div>
    </div>
</header>