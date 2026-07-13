<?php

/**
 * @var array $prohome    Full product listing (Codemoi\Model\Product::allHome()).
 * @var array $list_topsp Featured products (Codemoi\Model\Product::featured()).
 * @var array $list_bestsp Best-selling products (Codemoi\Model\Product::bestSellers()).
 */
?>
<!-- HERO -->
<section class="relative overflow-hidden">
    <!-- Ambient glow blobs -->
    <div class="pointer-events-none absolute -top-32 -left-32 h-96 w-96 rounded-full bg-brand-600/25 blur-3xl animate-float" aria-hidden="true"></div>
    <div class="pointer-events-none absolute top-1/3 -right-40 h-[28rem] w-[28rem] rounded-full bg-accent-600/20 blur-3xl animate-float" style="animation-delay:-2.5s" aria-hidden="true"></div>

    <div class="relative mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
        <div class="mx-auto max-w-3xl text-center animate-fade-up">
            <span class="glass inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-accent-400">
                <i class="fa-solid fa-bolt" aria-hidden="true"></i> Laptop gaming &amp; hiệu năng cao
            </span>
            <h1 class="mt-6 font-heading text-4xl font-extrabold leading-tight sm:text-5xl lg:text-6xl">
                Nâng tầm <span class="text-gradient">trải nghiệm</span><br>công nghệ của bạn
            </h1>
            <p class="mx-auto mt-6 max-w-xl text-base text-ink-500 sm:text-lg">
                Turbotech — laptop gaming và hiệu năng cao chính hãng, cấu hình mạnh mẽ, giá tốt nhất thị trường.
            </p>
            <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                <a href="index.php?act=product"
                    class="btn-glow inline-flex items-center justify-center gap-2 rounded-xl px-7 py-3.5 text-sm font-semibold text-white transition-transform">
                    Mua ngay <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                </a>
                <a href="index.php?act=introduce"
                    class="glass inline-flex items-center justify-center gap-2 rounded-xl px-7 py-3.5 text-sm font-semibold text-ink-800 transition-colors hover:text-brand-400">
                    Tìm hiểu thêm
                </a>
            </div>
        </div>

        <div class="mt-14 grid grid-cols-1 gap-6 md:grid-cols-2">
            <!-- Begin Hero Banner Item -->
            <div class="card-glow glass relative flex min-h-[240px] items-end overflow-hidden rounded-2xl bg-cover bg-center sm:min-h-[340px]"
                style="background-image:linear-gradient(0deg, rgba(5,6,10,.85), rgba(5,6,10,.15)), url('./assets/images/slider/anhmoi.jpg')">
                <div class="relative z-10 p-6 sm:p-8">
                    <a class="btn-glow inline-flex items-center justify-center gap-2 rounded-lg px-5 py-2.5 text-sm font-semibold text-white"
                        href="index.php?act=product">Mua ngay</a>
                </div>
            </div>
            <!-- Hero Banner Item End Here -->
            <!-- Begin Hero Banner Item -->
            <div class="card-glow glass relative flex min-h-[240px] items-end overflow-hidden rounded-2xl bg-cover bg-center sm:min-h-[340px]"
                style="background-image:linear-gradient(0deg, rgba(5,6,10,.85), rgba(5,6,10,.15)), url('./assets/images/slider/anhmoi2.jpg')">
                <div class="relative z-10 p-6 sm:p-8">
                    <a class="btn-glow inline-flex items-center justify-center gap-2 rounded-lg px-5 py-2.5 text-sm font-semibold text-white"
                        href="index.php?act=product">Mua ngay</a>
                </div>
            </div>
            <!-- Hero Banner Item End Here -->
        </div>
    </div>
</section>
<!-- HERO End Here -->

<!-- FREE SHIP -->
<section class="border-y border-ink-200 bg-ink-200/70 backdrop-blur-xl py-12 md:py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 gap-6 sm:grid-cols-4">
            <!-- Begin Shipping Information Item -->
            <div class="flex flex-col items-center gap-3 text-center">
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-brand-500/10 text-2xl text-brand-400 ring-1 ring-brand-500/30">
                    <i class="fa fa-truck" aria-hidden="true"></i>
                </div>
                <div>
                    <h5 class="font-heading font-semibold text-ink-900">Miễn phí Ship</h5>
                    <span class="text-sm text-ink-500">Miễn phí Ship khu vực Việt Nam</span>
                </div>
            </div>
            <!-- Shipping Information Item End Here -->
            <!-- Begin Shipping Information Item -->
            <div class="flex flex-col items-center gap-3 text-center">
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-brand-500/10 text-2xl text-brand-400 ring-1 ring-brand-500/30">
                    <i class="fa fa-credit-card" aria-hidden="true"></i>
                </div>
                <div>
                    <h5 class="font-heading font-semibold text-ink-900">Thanh toán khi nhận hàng</h5>
                    <span class="text-sm text-ink-500">Tùy chọn tiền mặt khi nhận hàng</span>
                </div>
            </div>
            <!-- Shipping Information Item End Here -->
            <!-- Begin Shipping Information Item -->
            <div class="flex flex-col items-center gap-3 text-center">
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-brand-500/10 text-2xl text-brand-400 ring-1 ring-brand-500/30">
                    <i class="fa fa-calendar" aria-hidden="true"></i>
                </div>
                <div>
                    <h5 class="font-heading font-semibold text-ink-900">Bảo hành 12 tháng</h5>
                    <span class="text-sm text-ink-500">Đổi trả trong vòng 7 ngày</span>
                </div>
            </div>
            <!-- Shipping Information Item End Here -->
            <!-- Begin Shipping Information Item -->
            <div class="flex flex-col items-center gap-3 text-center">
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-brand-500/10 text-2xl text-brand-400 ring-1 ring-brand-500/30">
                    <i class="fas fa-star-of-life" aria-hidden="true"></i>
                </div>
                <div>
                    <h5 class="font-heading font-semibold text-ink-900">Hỗ trợ trực tuyến 24/7</h5>
                    <span class="text-sm text-ink-500">Chúng tôi luôn sẵn sàng hỗ trợ</span>
                </div>
            </div>
            <!-- Shipping Information Item End Here -->
        </div>
    </div>
</section>
<!-- FREE SHIP End Here -->

<!-- PHẦN SẢN PHẨM TRANG HOME -->
<section class="py-12 md:py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Tab nav: plain vanilla-JS click handler (home-tab-trigger/home-tab-panel, script at
             the bottom of this file) — Bootstrap's data-bs-toggle="tab" data-api does NOT actually
             fire in this theme's bundled plugins.min.js (verified: bootstrap object loads but the
             Tab component's click delegation never toggles .active), so don't rely on it. Same
             proven pattern as the account-page tabs in view/user/myaccount.php. -->
        <div class="mb-8 flex flex-wrap justify-center gap-3">
            <a class="home-tab-trigger active inline-flex min-h-11 items-center justify-center rounded-full border border-ink-200 bg-ink-200/70 backdrop-blur-xl px-4 py-2.5 text-sm font-semibold text-ink-700 transition-colors hover:bg-ink-50 focus:outline-none focus:ring-2 focus:ring-brand-500 [&.active]:border-brand-600! [&.active]:bg-brand-600! [&.active]:text-white!"
                data-tab-target="new-arrival" href="#new-arrival"><span>Sản phẩm mới</span></a>
            <a class="home-tab-trigger inline-flex min-h-11 items-center justify-center rounded-full border border-ink-200 bg-ink-200/70 backdrop-blur-xl px-4 py-2.5 text-sm font-semibold text-ink-700 transition-colors hover:bg-ink-50 focus:outline-none focus:ring-2 focus:ring-brand-500 [&.active]:border-brand-600! [&.active]:bg-brand-600! [&.active]:text-white!"
                data-tab-target="bestseller" href="#bestseller"><span>Sản phẩm bán chạy</span></a>
            <a class="home-tab-trigger inline-flex min-h-11 items-center justify-center rounded-full border border-ink-200 bg-ink-200/70 backdrop-blur-xl px-4 py-2.5 text-sm font-semibold text-ink-700 transition-colors hover:bg-ink-50 focus:outline-none focus:ring-2 focus:ring-brand-500 [&.active]:border-brand-600! [&.active]:bg-brand-600! [&.active]:text-white!"
                data-tab-target="featured-products" href="#featured-products"><span>Sản phẩm nổi bật</span></a>
        </div>

        <div class="tab-content">
            <div id="new-arrival" data-tab-panel class="tab-pane active hidden [&.active]:block!" role="tabpanel">
                <div class="grid grid-cols-2 gap-6 sm:grid-cols-3 lg:grid-cols-4">
                    <!-- Phần show sản phẩm mới nhất -->
                    <?php
                    foreach ($prohome as $pro) { ?>
                        <div class="group relative overflow-hidden rounded-2xl border border-ink-200 bg-ink-200/70 backdrop-blur-xl shadow-sm transition-shadow hover:shadow-md">
                            <div class="relative aspect-square overflow-hidden bg-ink-100">
                                <a class="block h-full w-full"
                                    href="index.php?act=prodetail&idpro=<?= e($pro['id_pro']) ?>">
                                    <img class="h-full w-full object-cover" src="admin/uploads/<?= e($pro['img_pro']) ?>"
                                        alt="<?= e($pro['name_pro']) ?>" />
                                </a>
                                <span
                                    class="absolute top-2 right-2 inline-flex items-center rounded-full bg-brand-500/10 px-3 py-1 text-xs font-medium text-brand-700">Mới</span>
                                <?php if (!isset($pro['discount']) || $pro['discount'] <= 0) { ?>
                                <?php } else { ?>
                                    <span
                                        class="absolute top-2 left-2 rounded-full bg-red-600 px-2 py-1 text-xs font-bold text-white">-<?= e($pro['discount']) ?>%</span>
                                <?php } ?>
                                <?php if ((int) $pro['stock'] <= 0) { ?>
                                    <span class="absolute bottom-2 left-2 rounded-full bg-ink-100/80 px-2 py-1 text-xs font-bold text-white"><?= htmlspecialchars($pro['stock_message'] ?: 'Hết hàng') ?></span>
                                <?php } ?>
                            </div>
                            <div class="p-4">
                                <h6 class="mb-2">
                                    <a class="font-heading font-semibold text-ink-900 line-clamp-2 transition-colors hover:text-brand-600"
                                        href="index.php?act=prodetail&idpro=<?= e($pro['id_pro']) ?>"><?= e($pro['name_pro']) ?></a>
                                </h6>
                                <div class="mb-3 flex items-baseline gap-2">
                                    <?php if (!isset($pro['discount']) || $pro['discount'] <= 0) { ?>
                                        <span class="font-bold text-brand-600"><?= number_format($pro['price']) ?>₫</span>
                                    <?php } else { ?>
                                        <span
                                            class="font-bold text-brand-600"><?= number_format(($pro['price']) - (($pro['price']) * ($pro['discount']) / 100)) ?>₫</span>
                                        <span class="text-sm text-ink-600 line-through"><?= number_format($pro['price']) ?>₫</span>
                                    <?php } ?>
                                </div>
                                <?php if ((int) $pro['stock'] > 0) { ?>
                                    <form action="index.php?act=addtocart" method="post">
<?= \Codemoi\Core\Csrf::field() ?>
                                        <input type="hidden" name="id_pro" value="<?= e($pro['id_pro']) ?>">
                                        <input type="hidden" name="name_pro" value="<?= e($pro['name_pro']) ?>">
                                        <input type="hidden" name="img_pro" value="<?= e($pro['img_pro']) ?>">
                                        <input type="hidden" name="price" value="<?= e($pro['price']) ?>">
                                        <input type="submit" name="addtocart" value="Thêm vào giỏ"
                                            class="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                    </form>
                                <?php } else { ?>
                                    <button type="button" disabled
                                        class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-ink-100 px-5 py-2.5 text-sm font-semibold text-ink-600 cursor-not-allowed">
                                        <?= htmlspecialchars($pro['stock_message'] ?: 'Hết hàng') ?>
                                    </button>
                                <?php } ?>
                            </div>
                        </div>
                    <?php   } ?>
                    <!-- end phần show sản sản phẩm mới nhất -->
                </div>
            </div>
            <div id="bestseller" data-tab-panel class="tab-pane hidden [&.active]:block!" role="tabpanel">
                <div class="grid grid-cols-2 gap-6 sm:grid-cols-3 lg:grid-cols-4">
                    <!-- Sản phẩm bán chạy -->
                    <?php
                    foreach ($list_bestsp as $pro) { ?>
                        <div class="group relative overflow-hidden rounded-2xl border border-ink-200 bg-ink-200/70 backdrop-blur-xl shadow-sm transition-shadow hover:shadow-md">
                            <div class="relative aspect-square overflow-hidden bg-ink-100">
                                <a class="block h-full w-full" href="index.php?act=prodetail&idpro=<?= e($pro['id_pro']) ?>">
                                    <img class="h-full w-full object-cover" src="admin/uploads/<?= e($pro['img_pro']) ?>"
                                        alt="<?= e($pro['name_pro']) ?>" />
                                </a>
                                <span
                                    class="absolute top-2 right-2 inline-flex items-center rounded-full bg-brand-500/10 px-3 py-1 text-xs font-medium text-brand-700">Hot</span>
                                <?php if (!isset($pro['discount']) || $pro['discount'] <= 0) { ?>
                                <?php } else { ?>
                                    <span
                                        class="absolute top-2 left-2 rounded-full bg-red-600 px-2 py-1 text-xs font-bold text-white">-<?= e($pro['discount']) ?>%</span>
                                <?php } ?>
                                <?php if ((int) $pro['stock'] <= 0) { ?>
                                    <span class="absolute bottom-2 left-2 rounded-full bg-ink-100/80 px-2 py-1 text-xs font-bold text-white"><?= htmlspecialchars($pro['stock_message'] ?: 'Hết hàng') ?></span>
                                <?php } ?>
                            </div>
                            <div class="p-4">
                                <h6 class="mb-2">
                                    <a class="font-heading font-semibold text-ink-900 line-clamp-2 transition-colors hover:text-brand-600"
                                        href="index.php?act=prodetail&idpro=<?= e($pro['id_pro']) ?>"><?= e($pro['name_pro']) ?></a>
                                </h6>
                                <div class="mb-3 flex items-baseline gap-2">
                                    <?php if (!isset($pro['discount']) || $pro['discount'] <= 0) { ?>
                                        <span class="font-bold text-brand-600"><?= number_format($pro['price']) ?>₫</span>
                                    <?php } else { ?>
                                        <span
                                            class="font-bold text-brand-600"><?= number_format(($pro['price']) - (($pro['price']) * ($pro['discount']) / 100)) ?>₫</span>
                                        <span class="text-sm text-ink-600 line-through"><?= number_format($pro['price']) ?>₫</span>
                                    <?php } ?>
                                </div>
                                <?php if ((int) $pro['stock'] > 0) { ?>
                                    <form action="index.php?act=addtocart" method="post">
<?= \Codemoi\Core\Csrf::field() ?>
                                        <input type="hidden" name="id_pro" value="<?= e($pro['id_pro']) ?>">
                                        <input type="hidden" name="name_pro" value="<?= e($pro['name_pro']) ?>">
                                        <input type="hidden" name="img_pro" value="<?= e($pro['img_pro']) ?>">
                                        <input type="hidden" name="price" value="<?= e($pro['price']) ?>">
                                        <input type="submit" name="addtocart" value="Thêm vào giỏ"
                                            class="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                    </form>
                                <?php } else { ?>
                                    <button type="button" disabled
                                        class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-ink-100 px-5 py-2.5 text-sm font-semibold text-ink-600 cursor-not-allowed">
                                        <?= htmlspecialchars($pro['stock_message'] ?: 'Hết hàng') ?>
                                    </button>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                    <!-- End sản phẩm bán chạy -->
                </div>
            </div>

            <!-- show sản phẩm nổi bật -->
            <div id="featured-products" data-tab-panel class="tab-pane hidden [&.active]:block!" role="tabpanel">
                <div class="grid grid-cols-2 gap-6 sm:grid-cols-3 lg:grid-cols-4">
                    <!-- Phần show sản phẩm nổi bật -->
                    <?php
                    foreach ($list_topsp as $pro) { ?>
                        <div class="group relative overflow-hidden rounded-2xl border border-ink-200 bg-ink-200/70 backdrop-blur-xl shadow-sm transition-shadow hover:shadow-md">
                            <div class="relative aspect-square overflow-hidden bg-ink-100">
                                <a class="block h-full w-full" href="index.php?act=prodetail&idpro=<?= e($pro['id_pro']) ?>">
                                    <img class="h-full w-full object-cover" src="admin/uploads/<?= e($pro['img_pro']) ?>"
                                        alt="<?= e($pro['name_pro']) ?>" />
                                </a>
                                <span
                                    class="absolute top-2 right-2 inline-flex items-center rounded-full bg-brand-500/10 px-3 py-1 text-xs font-medium text-brand-700">Nổi
                                    bật</span>
                                <?php if (!isset($pro['discount']) || $pro['discount'] <= 0) { ?>
                                <?php } else { ?>
                                    <span
                                        class="absolute top-2 left-2 rounded-full bg-red-600 px-2 py-1 text-xs font-bold text-white">-<?= e($pro['discount']) ?>%</span>
                                <?php } ?>
                                <?php if ((int) $pro['stock'] <= 0) { ?>
                                    <span class="absolute bottom-2 left-2 rounded-full bg-ink-100/80 px-2 py-1 text-xs font-bold text-white"><?= htmlspecialchars($pro['stock_message'] ?: 'Hết hàng') ?></span>
                                <?php } ?>
                            </div>
                            <div class="p-4">
                                <h6 class="mb-2">
                                    <a class="font-heading font-semibold text-ink-900 line-clamp-2 transition-colors hover:text-brand-600"
                                        href="index.php?act=prodetail&idpro=<?= e($pro['id_pro']) ?>"><?= e($pro['name_pro']) ?></a>
                                </h6>
                                <div class="mb-3 flex items-baseline gap-2">
                                    <?php if (!isset($pro['discount']) || $pro['discount'] <= 0) { ?>
                                        <span class="font-bold text-brand-600"><?= number_format($pro['price']) ?>₫</span>
                                    <?php } else { ?>
                                        <span
                                            class="font-bold text-brand-600"><?= number_format(($pro['price']) - (($pro['price']) * ($pro['discount']) / 100)) ?>₫</span>
                                        <span class="text-sm text-ink-600 line-through"><?= number_format($pro['price']) ?>₫</span>
                                    <?php } ?>
                                </div>
                                <?php if ((int) $pro['stock'] > 0) { ?>
                                    <form action="index.php?act=addtocart" method="post">
<?= \Codemoi\Core\Csrf::field() ?>
                                        <input type="hidden" name="id_pro" value="<?= e($pro['id_pro']) ?>">
                                        <input type="hidden" name="name_pro" value="<?= e($pro['name_pro']) ?>">
                                        <input type="hidden" name="img_pro" value="<?= e($pro['img_pro']) ?>">
                                        <input type="hidden" name="price" value="<?= e($pro['price']) ?>">
                                        <input type="submit" name="addtocart" value="Thêm vào giỏ"
                                            class="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                    </form>
                                <?php } else { ?>
                                    <button type="button" disabled
                                        class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-ink-100 px-5 py-2.5 text-sm font-semibold text-ink-600 cursor-not-allowed">
                                        <?= htmlspecialchars($pro['stock_message'] ?: 'Hết hàng') ?>
                                    </button>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                    <!-- end phần show sản phẩm nổi bật -->
                </div>
            </div>
        </div>
    </div>
</section>
<!-- PHẦN SẢN PHẨM TRANG HOME End Here -->

<!-- Begin Turbotech Product With Content Area -->
<section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="relative flex min-h-[280px] items-center overflow-hidden rounded-2xl bg-ink-100 bg-cover bg-center sm:min-h-[420px]"
        style="background-image:url('./assets/images/banner/pcgami.png')">
        <div class="relative z-10 ml-auto w-full max-w-md p-6 text-right sm:p-10">
            <h2 class="font-heading text-2xl font-bold text-white sm:text-3xl">PC Gaming</h2>
            <p class="mt-3 text-sm text-white/90 sm:text-base">
                Cung cấp giải pháp PC và Laptop chính hãng, cấu hình tùy biến mạnh mẽ theo nhu cầu thực tế. Cam kết chất
                lượng dịch vụ, bảo hành dài hạn và hỗ trợ kỹ thuật tận tâm.
            </p>
            <div class="mt-5">
                <a class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2"
                    href="index.php?act=product&idcate=8">Mua ngay</a>
            </div>
        </div>
    </div>
</section>
<!-- Turbotech Product With Content Area End Here -->

<!-- Banner sale -->
<section class="mx-auto max-w-7xl px-4 pb-12 sm:px-6 lg:px-8 md:pb-16">
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <div class="overflow-hidden rounded-2xl border border-ink-200 shadow-sm transition-shadow hover:shadow-md">
            <a href="index.php?act=product&idcate=12">
                <img class="h-56 w-full object-cover" src="./assets/images/banner/lapmsi.jpg" alt="Tubotech" />
            </a>
        </div>
        <div class="overflow-hidden rounded-2xl border border-ink-200 shadow-sm transition-shadow hover:shadow-md">
            <a href="index.php?act=product&idcate=11">
                <img class="h-56 w-full object-cover" src="./assets/images/banner/lenovo.jpg" alt="Tubotech" />
            </a>
        </div>
    </div>
</section>
<!-- Tubotech Product With Two Columns Area End Here -->

<script>
    (function() {
        var triggers = document.querySelectorAll('.home-tab-trigger');
        var panels = document.querySelectorAll('[data-tab-panel]');

        triggers.forEach(function(trigger) {
            trigger.addEventListener('click', function(e) {
                e.preventDefault();
                var targetId = trigger.getAttribute('data-tab-target');

                panels.forEach(function(panel) {
                    panel.classList.toggle('active', panel.id === targetId);
                });

                triggers.forEach(function(t) {
                    t.classList.toggle('active', t === trigger);
                });
            });
        });
    })();
</script>