<?php
$count = \Codemoi\Model\Cart::count();
$total_amount = \Codemoi\Model\Cart::total();

// Site-wide flash toast: any controller can set these in $_SESSION right
// before a redirect and the message will still reach the user on the next
// page — a script echoed right before header('Location: ...') never runs,
// since browsers don't execute a 3xx response's body.
$flash_success = $_SESSION['flash_success'] ?? null;
$flash_error = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);
?>
<?php if ($flash_success): ?>
<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"success",title:<?= json_encode($flash_success) ?>,showConfirmButton:false,timer:3000}));</script>
<?php endif; ?>
<?php if ($flash_error): ?>
<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:<?= json_encode($flash_error) ?>,showConfirmButton:false,timer:3000}));</script>
<?php endif; ?>

<!-- Announcement bar (new) -->
<div class="relative overflow-hidden bg-linear-to-r from-brand-700 via-brand-600 to-accent-600 py-2 text-center text-xs font-semibold tracking-wide text-white sm:text-sm">
    <i class="fa-solid fa-truck-fast mr-2" aria-hidden="true"></i> Miễn phí vận chuyển toàn quốc — Bảo hành chính hãng 12 tháng
</div>

<header class="sticky top-0 z-40 border-b border-ink-300 bg-ink-50">
    <div class="mx-auto max-w-7xl px-3 sm:px-6 lg:px-8">
        <div class="flex h-20 items-center justify-between gap-2 sm:gap-4">
            <!-- Logo -->
            <a href="index.php" class="flex shrink-0 items-center rounded-md focus:outline-none focus:ring-2 focus:ring-brand-500">
                <img src="./assets/images/menu/logo/logo-wordmark-light.svg" alt="Logo Turbotech" class="h-8 w-auto sm:h-9" />
            </a>

            <!-- Desktop nav -->
            <nav class="hidden items-center gap-6 lg:flex">
                <a href="index.php"
                    class="relative py-2 text-sm font-medium uppercase tracking-wide text-ink-700 transition-colors after:absolute after:-bottom-0.5 after:left-0 after:h-px after:w-0 after:bg-brand-500 after:transition-all hover:text-brand-600 hover:after:w-full focus:outline-none focus:ring-2 focus:ring-brand-500">Trang
                    chủ</a>

                <div class="group relative">
                    <a href="index.php?act=product"
                        class="inline-flex items-center gap-1.5 py-2 text-sm font-medium uppercase tracking-wide text-ink-700 transition-colors hover:text-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500">
                        Sản phẩm
                        <i class="fa-solid fa-chevron-down text-[10px]"></i>
                    </a>
                    <div
                        class="card-boutique invisible absolute left-0 top-full z-50 w-56 rounded-md py-2 opacity-0 -translate-y-1 transition-all duration-200 ease-out group-hover:visible group-hover:opacity-100 group-hover:translate-y-0">
                        <?php
                        foreach ($listcate ?? [] as $cate) {
                            extract($cate);
                            $linkpro = "index.php?act=product&idcate=" . $id_cate;
                            echo '<a href="' . e($linkpro) . '" class="block px-4 py-2 text-sm text-ink-700 hover:bg-ink-100 hover:text-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500">' . e($name_cate) . '</a>';
                        }
                        ?>
                    </div>
                </div>

                <a href="index.php?act=introduce"
                    class="relative py-2 text-sm font-medium uppercase tracking-wide text-ink-700 transition-colors after:absolute after:-bottom-0.5 after:left-0 after:h-px after:w-0 after:bg-brand-500 after:transition-all hover:text-brand-600 hover:after:w-full focus:outline-none focus:ring-2 focus:ring-brand-500">Giới
                    thiệu</a>
                <a href="index.php?act=contact"
                    class="relative py-2 text-sm font-medium uppercase tracking-wide text-ink-700 transition-colors after:absolute after:-bottom-0.5 after:left-0 after:h-px after:w-0 after:bg-brand-500 after:transition-all hover:text-brand-600 hover:after:w-full focus:outline-none focus:ring-2 focus:ring-brand-500">Liên
                    hệ</a>
                <a href="index.php?act=question"
                    class="relative py-2 text-sm font-medium uppercase tracking-wide text-ink-700 transition-colors after:absolute after:-bottom-0.5 after:left-0 after:h-px after:w-0 after:bg-brand-500 after:transition-all hover:text-brand-600 hover:after:w-full focus:outline-none focus:ring-2 focus:ring-brand-500">Hỏi
                    đáp</a>
            </nav>

            <!-- Desktop search -->
            <form action="index.php" method="get" class="hidden max-w-xs flex-1 md:flex">
                <input type="hidden" name="act" value="product">
                <label for="header-search" class="sr-only">Tìm kiếm sản phẩm</label>
                <div class="relative w-full border-b border-ink-300 focus-within:border-brand-500">
                    <input id="header-search" name="kyw" type="text" required
                        placeholder="Tìm kiếm sản phẩm ..."
                        class="block w-full bg-transparent py-2 pl-1 pr-9 text-sm text-ink-900 placeholder:text-ink-500 focus:outline-none" />
                    <button type="submit" name="btn_search" aria-label="Tìm kiếm"
                        class="absolute right-0 top-1/2 flex h-8 w-8 -translate-y-1/2 items-center justify-center text-ink-500 transition-colors hover:text-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500">
                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    </button>
                </div>
            </form>

            <!-- Right icons -->
            <div class="flex shrink-0 items-center gap-0.5 sm:gap-1">
                <!-- Cart -->
                <div class="group relative">
                    <a href="index.php?act=viewcart" aria-label="Giỏ hàng"
                        class="relative flex h-11 w-11 items-center justify-center rounded-full text-ink-700 transition-colors hover:bg-ink-100 focus:outline-none focus:ring-2 focus:ring-brand-500">
                        <i class="fa-solid fa-cart-shopping text-lg"></i>
                        <span
                            class="absolute right-0.5 top-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full bg-brand-600 text-[11px] font-bold text-white"><?= e($count) ?></span>
                    </a>
                    <div
                        class="card-boutique invisible absolute right-0 top-full z-50 w-80 rounded-md opacity-0 -translate-y-1 transition-all duration-200 ease-out group-hover:visible group-hover:opacity-100 group-hover:translate-y-0">
                        <?php if (empty($_SESSION['mycart'])) { ?>
                            <p class="p-6 text-center text-sm text-ink-500">Bạn chưa thêm sản phẩm nào vào giỏ hàng !</p>
                        <?php } else { ?>
                            <ul class="max-h-80 divide-y divide-ink-100 overflow-y-auto">
                                <?php foreach ($_SESSION['mycart'] as $cart) { ?>
                                    <li class="flex gap-3 p-3">
                                        <a href="index.php?act=prodetail&idpro=<?= e($cart[0]) ?>"
                                            class="relative h-14 w-14 shrink-0 overflow-hidden rounded-lg bg-ink-100">
                                            <img src="admin/uploads/<?= e($cart[2]) ?>" alt="<?= e($cart[1]) ?>"
                                                class="h-full w-full object-cover" />
                                            <span
                                                class="absolute bottom-0 right-0 rounded-tl-md bg-ink-100/80 px-1 text-[10px] font-semibold text-white"><?= e($cart[4]) ?>x</span>
                                        </a>
                                        <div class="min-w-0 flex-1">
                                            <a href="index.php?act=prodetail&idpro=<?= e($cart[0]) ?>"
                                                class="line-clamp-2 text-sm font-medium text-ink-900 hover:text-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500"><?= e($cart[1]) ?></a>
                                            <p class="mt-1 text-sm font-semibold text-brand-600"><?= number_format($cart[3]) ?>₫</p>
                                        </div>
                                    </li>
                                <?php } ?>
                            </ul>
                            <div class="border-t border-ink-200 p-4">
                                <div class="flex items-center justify-between text-sm font-semibold text-ink-900">
                                    <span>Tổng tiền</span>
                                    <span class="text-brand-600"><?= number_format($total_amount) ?>₫</span>
                                </div>
                                <a href="index.php?act=viewcart"
                                    class="mt-3 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">Xem
                                    giỏ hàng</a>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <!-- Account (hidden on the narrowest screens to avoid crowding the header;
                     reachable via the mobile menu drawer instead — see #mobile-menu below) -->
                <div class="group relative hidden sm:block">
                    <?php if (!isset($_SESSION['user'])) { ?>
                        <a href="#"
                            class="flex h-11 items-center gap-2 rounded-full px-3 text-sm font-medium text-ink-700 transition-colors hover:bg-ink-100 focus:outline-none focus:ring-2 focus:ring-brand-500">
                            <i class="fa-solid fa-user text-lg"></i>
                            <span class="hidden sm:inline">Tài khoản</span>
                        </a>
                    <?php } else { ?>
                        <a href="#"
                            class="flex h-11 items-center gap-2 rounded-full px-3 text-sm font-medium text-ink-700 transition-colors hover:bg-ink-100 focus:outline-none focus:ring-2 focus:ring-brand-500">
                            <?php if (isset($_SESSION['user']['img_user']) && $_SESSION['user']['img_user'] != '') { ?>
                                <img src="uploads/<?= e($_SESSION['user']['img_user']) ?>" alt=""
                                    class="h-7 w-7 rounded-full object-cover" />
                            <?php } else { ?>
                                <i class="fa-solid fa-user text-lg"></i>
                            <?php } ?>
                            <span class="hidden max-w-32 truncate sm:inline"><?= e($_SESSION['user']['full_name']) ?></span>
                        </a>
                    <?php } ?>
                    <div
                        class="card-boutique invisible absolute right-0 top-full z-50 w-56 rounded-md py-2 opacity-0 -translate-y-1 transition-all duration-200 ease-out group-hover:visible group-hover:opacity-100 group-hover:translate-y-0">
                        <?php if (!isset($_SESSION['user'])) { ?>
                            <a href="index.php?act=login"
                                class="flex items-center gap-2 px-4 py-2 text-sm text-ink-700 hover:bg-ink-50 hover:text-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500"><i
                                    class="fa-solid fa-right-to-bracket w-4"></i> Đăng nhập</a>
                        <?php } else if ($_SESSION['user']['role'] == 1) { ?>
                            <a href="./admin/index.php"
                                class="flex items-center gap-2 px-4 py-2 text-sm text-ink-700 hover:bg-ink-50 hover:text-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500"><i
                                    class="fa-solid fa-gears w-4"></i> Vào trang Admin</a>
                            <a href="index.php?act=myaccount"
                                class="flex items-center gap-2 px-4 py-2 text-sm text-ink-700 hover:bg-ink-50 hover:text-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500"><i
                                    class="fa-solid fa-circle-info w-4"></i> Thông tin tài khoản</a>
                            <a href="index.php?act=logout"
                                data-confirm="Bạn chắc chắc muốn đăng xuất tài khoản?"
                                class="flex items-center gap-2 px-4 py-2 text-sm text-ink-700 hover:bg-ink-50 hover:text-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500"><i
                                    class="fa-solid fa-right-from-bracket w-4"></i> Đăng xuất</a>
                        <?php } else { ?>
                            <a href="index.php?act=myaccount"
                                class="flex items-center gap-2 px-4 py-2 text-sm text-ink-700 hover:bg-ink-50 hover:text-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500"><i
                                    class="fa-solid fa-circle-info w-4"></i> Thông tin tài khoản</a>
                            <a href="index.php?act=logout"
                                data-confirm="Bạn chắc chắc muốn đăng xuất tài khoản?"
                                class="flex items-center gap-2 px-4 py-2 text-sm text-ink-700 hover:bg-ink-50 hover:text-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500"><i
                                    class="fa-solid fa-right-from-bracket w-4"></i> Đăng xuất</a>
                        <?php } ?>
                    </div>
                </div>

                <!-- Mobile menu toggle -->
                <button type="button" id="mobile-menu-btn" aria-label="Mở menu" aria-expanded="false"
                    aria-controls="mobile-menu"
                    class="flex h-11 w-11 items-center justify-center rounded-full text-ink-700 transition-colors hover:bg-ink-100 focus:outline-none focus:ring-2 focus:ring-brand-500 lg:hidden">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Mobile search -->
        <form action="index.php" method="get" class="pb-3 md:hidden">
            <input type="hidden" name="act" value="product">
            <label for="header-search-mobile" class="sr-only">Tìm kiếm sản phẩm</label>
            <div class="relative w-full border-b border-ink-300 focus-within:border-brand-500">
                <input id="header-search-mobile" name="kyw" type="text" required
                    placeholder="Tìm kiếm sản phẩm ..."
                    class="block w-full bg-transparent py-2.5 pl-1 pr-9 text-sm text-ink-900 placeholder:text-ink-500 focus:outline-none" />
                <button type="submit" name="btn_search" aria-label="Tìm kiếm"
                    class="absolute right-0 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center text-ink-500 transition-colors hover:text-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Mobile nav -->
    <nav id="mobile-menu"
        class="grid grid-rows-[0fr] overflow-hidden border-t border-ink-300 bg-ink-50 transition-[grid-template-rows] duration-300 ease-in-out lg:hidden">
        <div class="min-h-0 overflow-hidden">
        <div class="mx-auto max-w-7xl space-y-1 px-4 py-4 sm:px-6">
            <a href="index.php"
                class="block rounded-lg px-3 py-2.5 text-sm font-medium text-ink-700 hover:bg-ink-50 focus:outline-none focus:ring-2 focus:ring-brand-500">Trang
                chủ</a>
            <details class="group/cate rounded-lg">
                <summary
                    class="flex cursor-pointer list-none items-center justify-between rounded-lg px-3 py-2.5 text-sm font-medium text-ink-700 hover:bg-ink-50 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <span>Sản phẩm</span>
                    <i class="fa-solid fa-chevron-down text-xs transition-transform group-open/cate:rotate-180"></i>
                </summary>
                <div class="ml-3 mt-1 space-y-1 border-l border-ink-200 pl-3">
                    <?php
                    foreach ($listcate as $cate) {
                        extract($cate);
                        $linkpro = "index.php?act=product&idcate=" . $id_cate;
                        echo '<a href="' . e($linkpro) . '" class="block rounded-lg px-3 py-2 text-sm text-ink-700 hover:bg-ink-50 hover:text-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500">' . e($name_cate) . '</a>';
                    }
                    ?>
                </div>
            </details>
            <a href="index.php?act=introduce"
                class="block rounded-lg px-3 py-2.5 text-sm font-medium text-ink-700 hover:bg-ink-50 focus:outline-none focus:ring-2 focus:ring-brand-500">Giới
                thiệu</a>
            <a href="index.php?act=contact"
                class="block rounded-lg px-3 py-2.5 text-sm font-medium text-ink-700 hover:bg-ink-50 focus:outline-none focus:ring-2 focus:ring-brand-500">Liên
                hệ</a>
            <a href="index.php?act=question"
                class="block rounded-lg px-3 py-2.5 text-sm font-medium text-ink-700 hover:bg-ink-50 focus:outline-none focus:ring-2 focus:ring-brand-500">Hỏi
                đáp</a>

            <!-- Account links (sm:hidden — the top-level account icon takes over from the
                 sm breakpoint up) -->
            <div class="mt-2 space-y-1 border-t border-ink-200 pt-2 sm:hidden">
                <?php if (!isset($_SESSION['user'])) { ?>
                    <a href="index.php?act=login"
                        class="flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-medium text-ink-700 hover:bg-ink-50 focus:outline-none focus:ring-2 focus:ring-brand-500"><i
                            class="fa-solid fa-right-to-bracket w-4"></i> Đăng nhập</a>
                <?php } else { ?>
                    <?php if ($_SESSION['user']['role'] == 1) { ?>
                        <a href="./admin/index.php"
                            class="flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-medium text-ink-700 hover:bg-ink-50 focus:outline-none focus:ring-2 focus:ring-brand-500"><i
                                class="fa-solid fa-gears w-4"></i> Vào trang Admin</a>
                    <?php } ?>
                    <a href="index.php?act=myaccount"
                        class="flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-medium text-ink-700 hover:bg-ink-50 focus:outline-none focus:ring-2 focus:ring-brand-500"><i
                            class="fa-solid fa-circle-info w-4"></i> Thông tin tài khoản (<?= e($_SESSION['user']['full_name']) ?>)</a>
                    <a href="index.php?act=logout"
                        data-confirm="Bạn chắc chắc muốn đăng xuất tài khoản?"
                        class="flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-medium text-ink-700 hover:bg-ink-50 focus:outline-none focus:ring-2 focus:ring-brand-500"><i
                            class="fa-solid fa-right-from-bracket w-4"></i> Đăng xuất</a>
                <?php } ?>
            </div>
        </div>
        </div>
    </nav>
</header>

<script>
    (function() {
        var btn = document.getElementById('mobile-menu-btn');
        var menu = document.getElementById('mobile-menu');
        if (!btn || !menu) return;
        btn.addEventListener('click', function() {
            var isNowOpen = menu.classList.toggle('grid-rows-[1fr]');
            menu.classList.toggle('grid-rows-[0fr]', !isNowOpen);
            btn.setAttribute('aria-expanded', String(isNowOpen));
        });
    })();
</script>