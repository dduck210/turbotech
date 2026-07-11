<?php
$current_act = $_GET['act'] ?? 'dashboard';
if ($current_act === '/') {
    $current_act = 'dashboard';
}
$productsActive = in_array($current_act, ['add_product', 'list_product', 'edit_product'], true);
$categoriesActive = in_array($current_act, ['add_category', 'list_category', 'edit_category'], true);

function nav_link_class(bool $active): string
{
    return $active
        ? 'flex items-center px-3 py-2.5 rounded-lg bg-slate-800 text-white transition-colors group'
        : 'flex items-center px-3 py-2.5 rounded-lg hover:bg-slate-800 text-slate-300 transition-colors group';
}

function nav_icon_class(bool $active): string
{
    return $active
        ? 'w-6 text-center text-brand-500 transition-colors'
        : 'w-6 text-center text-slate-400 group-hover:text-white transition-colors';
}

function nav_submenu_toggle_class(bool $active): string
{
    return $active
        ? 'px-3 py-2.5 rounded-lg bg-slate-800 text-white transition-colors flex items-center justify-between cursor-pointer group'
        : 'px-3 py-2.5 rounded-lg hover:bg-slate-800 text-slate-300 transition-colors flex items-center justify-between cursor-pointer group';
}
?>
<div id="mobile-sidebar-backdrop" class="hidden fixed inset-0 bg-slate-900/50 z-30 md:hidden"></div>
<aside id="mobile-sidebar-nav" class="w-64 bg-slate-900 text-slate-300 shrink-0 hidden md:flex flex-col shadow-2xl fixed inset-y-0 left-0 z-40 md:relative md:inset-auto md:z-20">
    <!-- Logo -->
    <a href="index.php?act=admin" class="flex items-center justify-center h-20 border-b border-slate-800 hover:bg-slate-800/50 transition-colors group">
        <div class="bg-brand-600 text-white p-2.5 rounded-xl group-hover:scale-110 transition-transform shadow-lg shadow-brand-500/30 flex items-center justify-center">
            <i class="fas fa-laugh-wink text-xl"></i>
        </div>
        <span class="ml-3 font-heading font-bold text-lg text-white tracking-wide">Turbotech Admin</span>
    </a>

    <!-- Nav Links -->
    <div class="flex-1 overflow-y-auto py-6 px-4 space-y-1">
        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-2 px-3">Main</div>
        <a href="index.php?act=admin" class="<?= nav_link_class($current_act === 'dashboard') ?>">
            <i class="fas fa-tachometer-alt <?= nav_icon_class($current_act === 'dashboard') ?>"></i>
            <span class="ml-3 font-medium">Bảng điều khiển</span>
        </a>

        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-6 px-3">Quản lý</div>

        <!-- Products -->
        <div class="space-y-1">
            <div class="<?= nav_submenu_toggle_class($productsActive) ?>" onclick="toggleSubmenu('submenu-products')">
                <div class="flex items-center">
                    <i class="fas fa-box <?= nav_icon_class($productsActive) ?>"></i>
                    <span class="ml-3 font-medium">Sản Phẩm</span>
                </div>
                <i id="submenu-products-chevron" class="fas fa-chevron-down text-xs text-slate-500 transition-transform duration-300 <?= $productsActive ? 'rotate-180' : '' ?>"></i>
            </div>
            <div id="submenu-products" class="grid transition-[grid-template-rows] duration-300 ease-in-out <?= $productsActive ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]' ?>">
                <div class="overflow-hidden">
                    <div class="pl-11 pr-3 py-2 space-y-2 text-sm">
                        <a href="index.php?act=add_product" class="block <?= $current_act === 'add_product' ? 'text-white font-semibold' : 'text-slate-400 hover:text-white' ?> transition-colors py-1">Thêm sản phẩm</a>
                        <a href="index.php?act=list_product" class="block <?= $current_act === 'list_product' ? 'text-white font-semibold' : 'text-slate-400 hover:text-white' ?> transition-colors py-1">Danh sách sản phẩm</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories -->
        <div class="space-y-1">
            <div class="<?= nav_submenu_toggle_class($categoriesActive) ?>" onclick="toggleSubmenu('submenu-categories')">
                <div class="flex items-center">
                    <i class="fas fa-tags <?= nav_icon_class($categoriesActive) ?>"></i>
                    <span class="ml-3 font-medium">Danh Mục</span>
                </div>
                <i id="submenu-categories-chevron" class="fas fa-chevron-down text-xs text-slate-500 transition-transform duration-300 <?= $categoriesActive ? 'rotate-180' : '' ?>"></i>
            </div>
            <div id="submenu-categories" class="grid transition-[grid-template-rows] duration-300 ease-in-out <?= $categoriesActive ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]' ?>">
                <div class="overflow-hidden">
                    <div class="pl-11 pr-3 py-2 space-y-2 text-sm">
                        <a href="index.php?act=add_category" class="block <?= $current_act === 'add_category' ? 'text-white font-semibold' : 'text-slate-400 hover:text-white' ?> transition-colors py-1">Thêm danh mục</a>
                        <a href="index.php?act=list_category" class="block <?= $current_act === 'list_category' ? 'text-white font-semibold' : 'text-slate-400 hover:text-white' ?> transition-colors py-1">Danh sách danh mục</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users -->
        <a href="index.php?act=list_user" class="<?= nav_link_class(in_array($current_act, ['list_user', 'edit_user'], true)) ?>">
            <i class="fas fa-users <?= nav_icon_class(in_array($current_act, ['list_user', 'edit_user'], true)) ?>"></i>
            <span class="ml-3 font-medium">Khách hàng</span>
        </a>

        <!-- Orders -->
        <a href="index.php?act=list_bill" class="<?= nav_link_class(in_array($current_act, ['list_bill', 'edit_bill', 'billdetail'], true)) ?>">
            <i class="fas fa-shopping-cart <?= nav_icon_class(in_array($current_act, ['list_bill', 'edit_bill', 'billdetail'], true)) ?>"></i>
            <span class="ml-3 font-medium">Đơn hàng</span>
        </a>

        <!-- Coupons -->
        <a href="index.php?act=list_coupon" class="<?= nav_link_class(in_array($current_act, ['list_coupon', 'add_coupon', 'edit_coupon'], true)) ?>">
            <i class="fas fa-ticket-alt <?= nav_icon_class(in_array($current_act, ['list_coupon', 'add_coupon', 'edit_coupon'], true)) ?>"></i>
            <span class="ml-3 font-medium">Mã giảm giá</span>
        </a>

        <!-- Comments -->
        <a href="index.php?act=list_cmt" class="<?= nav_link_class($current_act === 'list_cmt') ?>">
            <i class="fas fa-comments <?= nav_icon_class($current_act === 'list_cmt') ?>"></i>
            <span class="ml-3 font-medium">Bình luận</span>
        </a>

        <!-- Q&A -->
        <a href="index.php?act=list_ques" class="<?= nav_link_class($current_act === 'list_ques') ?>">
            <i class="fas fa-question-circle <?= nav_icon_class($current_act === 'list_ques') ?>"></i>
            <span class="ml-3 font-medium">Hỏi đáp</span>
        </a>

        <!-- Statistics -->
        <a href="index.php?act=list_thongke" class="<?= nav_link_class($current_act === 'list_thongke') ?>">
            <i class="fas fa-chart-bar <?= nav_icon_class($current_act === 'list_thongke') ?>"></i>
            <span class="ml-3 font-medium">Thống kê</span>
        </a>
    </div>
</aside>
