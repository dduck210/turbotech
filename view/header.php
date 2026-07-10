<?php
$count = \Codemoi\Model\Cart::count();
$total_amount = \Codemoi\Model\Cart::total();
?>

<header class="sticky top-0 z-40 border-b border-ink-200 bg-white/90 backdrop-blur">
    <div class="mx-auto max-w-7xl px-3 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between gap-1 sm:gap-3">
            <!-- Logo -->
            <a href="index.php" class="flex shrink-0 items-center gap-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500">
                <img src="./src/image/menu/logo/logo4.png" alt="Logo Turbotech" class="h-8 w-auto sm:h-10" />
            </a>

            <!-- Desktop nav -->
            <nav class="hidden items-center gap-1 lg:flex">
                <a href="index.php"
                    class="rounded-lg px-3 py-2 text-sm font-medium text-ink-700 transition-colors hover:bg-ink-50 hover:text-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500">Trang
                    chủ</a>

                <div class="group relative">
                    <a href="index.php?act=product"
                        class="inline-flex items-center gap-1 rounded-lg px-3 py-2 text-sm font-medium text-ink-700 transition-colors hover:bg-ink-50 hover:text-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500">
                        Sản phẩm
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </a>
                    <div
                        class="invisible absolute left-0 top-full z-50 w-56 rounded-xl border border-ink-200 bg-white py-2 opacity-0 shadow-lg transition-all group-hover:visible group-hover:opacity-100">
                        <?php
                        foreach ($listcate ?? [] as $cate) {
                            extract($cate);
                            $linkpro = "index.php?act=product&idcate=" . $id_cate;
                            echo '<a href="' . $linkpro . '" class="block px-4 py-2 text-sm text-ink-700 hover:bg-ink-50 hover:text-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500">' . $name_cate . '</a>';
                        }
                        ?>
                    </div>
                </div>

                <a href="index.php?act=introduce"
                    class="rounded-lg px-3 py-2 text-sm font-medium text-ink-700 transition-colors hover:bg-ink-50 hover:text-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500">Giới
                    thiệu</a>
                <a href="index.php?act=contact"
                    class="rounded-lg px-3 py-2 text-sm font-medium text-ink-700 transition-colors hover:bg-ink-50 hover:text-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500">Liên
                    hệ</a>
                <a href="index.php?act=question"
                    class="rounded-lg px-3 py-2 text-sm font-medium text-ink-700 transition-colors hover:bg-ink-50 hover:text-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500">Hỏi
                    đáp</a>
            </nav>

            <!-- Desktop search -->
            <form action="index.php" method="get" class="hidden max-w-sm flex-1 md:flex">
                <input type="hidden" name="act" value="product">
                <label for="header-search" class="sr-only">Tìm kiếm sản phẩm</label>
                <div class="relative w-full">
                    <input id="header-search" name="kyw" type="text" required
                        placeholder="Nhập từ khóa tìm kiếm ..."
                        class="block w-full rounded-full border border-ink-200 bg-ink-50 py-2.5 pl-4 pr-11 text-sm text-ink-900 placeholder:text-ink-300 focus:border-brand-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500" />
                    <button type="submit" name="btn_search" aria-label="Tìm kiếm"
                        class="absolute right-1 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full text-ink-500 transition-colors hover:bg-ink-100 hover:text-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500">
                        <i class="fa-solid fa-magnifying-glass"></i>
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
                            class="absolute right-0.5 top-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full bg-brand-600 text-[11px] font-bold text-white"><?= $count ?></span>
                    </a>
                    <div
                        class="invisible absolute right-0 top-full z-50 w-80 rounded-xl border border-ink-200 bg-white opacity-0 shadow-lg transition-all group-hover:visible group-hover:opacity-100">
                        <?php if (empty($_SESSION['mycart'])) { ?>
                        <p class="p-6 text-center text-sm text-ink-500">Bạn chưa thêm sản phẩm nào vào giỏ hàng !</p>
                        <?php } else { ?>
                        <ul class="max-h-80 divide-y divide-ink-100 overflow-y-auto">
                            <?php foreach ($_SESSION['mycart'] as $cart) { ?>
                            <li class="flex gap-3 p-3">
                                <a href="index.php?act=prodetail&idpro=<?= $cart[0] ?>"
                                    class="relative h-14 w-14 shrink-0 overflow-hidden rounded-lg bg-ink-100">
                                    <img src="admin/uploads/<?= $cart[2] ?>" alt="<?= $cart[1] ?>"
                                        class="h-full w-full object-cover" />
                                    <span
                                        class="absolute bottom-0 right-0 rounded-tl-md bg-ink-900/80 px-1 text-[10px] font-semibold text-white"><?= $cart[4] ?>x</span>
                                </a>
                                <div class="min-w-0 flex-1">
                                    <a href="index.php?act=prodetail&idpro=<?= $cart[0] ?>"
                                        class="line-clamp-2 text-sm font-medium text-ink-900 hover:text-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500"><?= $cart[1] ?></a>
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
                        <img src="uploads/<?= $_SESSION['user']['img_user'] ?>" alt=""
                            class="h-7 w-7 rounded-full object-cover" />
                        <?php } else { ?>
                        <i class="fa-solid fa-user text-lg"></i>
                        <?php } ?>
                        <span class="hidden max-w-32 truncate sm:inline"><?= $_SESSION['user']['full_name'] ?></span>
                    </a>
                    <?php } ?>
                    <div
                        class="invisible absolute right-0 top-full z-50 w-56 rounded-xl border border-ink-200 bg-white py-2 opacity-0 shadow-lg transition-all group-hover:visible group-hover:opacity-100">
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
            <div class="relative w-full">
                <input id="header-search-mobile" name="kyw" type="text" required
                    placeholder="Nhập từ khóa tìm kiếm ..."
                    class="block w-full rounded-full border border-ink-200 bg-ink-50 py-2.5 pl-4 pr-11 text-sm text-ink-900 placeholder:text-ink-300 focus:border-brand-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500" />
                <button type="submit" name="btn_search" aria-label="Tìm kiếm"
                    class="absolute right-1 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full text-ink-500 transition-colors hover:bg-ink-100 hover:text-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Mobile nav -->
    <nav id="mobile-menu" class="hidden border-t border-ink-200 bg-white lg:hidden">
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
                        echo '<a href="' . $linkpro . '" class="block rounded-lg px-3 py-2 text-sm text-ink-700 hover:bg-ink-50 hover:text-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500">' . $name_cate . '</a>';
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
                        class="fa-solid fa-circle-info w-4"></i> Thông tin tài khoản (<?= $_SESSION['user']['full_name'] ?>)</a>
                <a href="index.php?act=logout"
                    data-confirm="Bạn chắc chắc muốn đăng xuất tài khoản?"
                    class="flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-medium text-ink-700 hover:bg-ink-50 focus:outline-none focus:ring-2 focus:ring-brand-500"><i
                        class="fa-solid fa-right-from-bracket w-4"></i> Đăng xuất</a>
                <?php } ?>
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
            var isNowHidden = menu.classList.toggle('hidden');
            btn.setAttribute('aria-expanded', String(!isNowHidden));
        });
    })();
</script>
