<?php

/**
 * @var array $prohome    Full product listing (Codemoi\Model\Product::allHome()).
 * @var array $list_topsp Featured products (Codemoi\Model\Product::featured()).
 * @var array $list_bestsp Best-selling products (Codemoi\Model\Product::bestSellers()).
 */
?>
<!-- HERO: asymmetric editorial split (was: centered hero + glow blobs) -->
<section class="border-b border-ink-200">
    <div class="mx-auto grid max-w-7xl grid-cols-1 items-center gap-10 px-4 py-14 sm:px-6 lg:grid-cols-[1.1fr_1fr] lg:gap-16 lg:px-8 lg:py-20">
        <div class="animate-fade-up">
            <span class="text-serif-accent text-sm font-semibold uppercase tracking-[0.2em]">Laptop gaming &amp; hiệu năng cao</span>
            <h1 class="mt-5 font-heading text-4xl font-semibold leading-[1.1] text-ink-900 sm:text-5xl lg:text-6xl">
                Nâng tầm trải nghiệm<br>công nghệ của bạn
            </h1>
            <span class="mt-6 block h-px w-16 bg-brand-500"></span>
            <p class="mt-6 max-w-md text-base leading-relaxed text-ink-600">
                Turbotech — laptop gaming và hiệu năng cao chính hãng, cấu hình mạnh mẽ, giá tốt nhất thị trường.
            </p>
            <div class="mt-8 flex flex-wrap items-center gap-5">
                <a href="index.php?act=product"
                    class="btn-boutique inline-flex items-center justify-center gap-2 rounded-md px-7 py-3 text-sm font-semibold">
                    Mua ngay <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                </a>
                <a href="index.php?act=introduce"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-ink-800 underline decoration-brand-400 decoration-2 underline-offset-4 transition-colors hover:text-brand-600">
                    Tìm hiểu thêm
                </a>
            </div>

            <!-- Trust stats: thin-divider strip (was: 3-up gradient-text stat cards) -->
            <div class="mt-12 flex divide-x divide-ink-200 border-t border-ink-200 pt-6">
                <div class="flex-1 pr-4">
                    <div class="font-heading text-3xl font-semibold text-ink-900">500+</div>
                    <div class="mt-1 text-xs text-ink-500 sm:text-sm">Khách hàng tin dùng</div>
                </div>
                <div class="flex-1 px-4">
                    <div class="font-heading text-3xl font-semibold text-ink-900">50+</div>
                    <div class="mt-1 text-xs text-ink-500 sm:text-sm">Mẫu laptop chính hãng</div>
                </div>
                <div class="flex-1 pl-4">
                    <div class="font-heading text-3xl font-semibold text-ink-900">24/7</div>
                    <div class="mt-1 text-xs text-ink-500 sm:text-sm">Hỗ trợ kỹ thuật</div>
                </div>
            </div>
        </div>

        <div class="relative animate-fade-up" style="animation-delay:.1s">
            <div class="card-boutique overflow-hidden rounded-lg">
                <img src="./assets/images/slider/anhmoi.jpg" alt="Turbotech" class="h-72 w-full object-cover sm:h-96 lg:h-[28rem]" />
            </div>
            <div class="card-boutique absolute -bottom-6 -left-6 hidden rounded-md px-5 py-4 sm:block">
                <p class="font-heading text-xl font-semibold text-brand-600">Bảo hành 12 tháng</p>
                <p class="mt-1 text-xs text-ink-500">Chính hãng &amp; đổi trả 7 ngày</p>
            </div>
        </div>
    </div>
</section>
<!-- HERO End Here -->

<!-- WHY US — quiet icon column row (was: bento glass grid) -->
<section class="reveal-on-scroll py-14 md:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-10 max-w-xl">
            <span class="text-serif-accent text-xs font-semibold uppercase tracking-[0.2em]">Vì sao chọn Turbotech</span>
            <h2 class="mt-2 font-heading text-2xl font-semibold text-ink-900 sm:text-3xl">Cam kết chất lượng &amp; dịch vụ</h2>
        </div>
        <div class="grid grid-cols-1 divide-y divide-ink-200 border-y border-ink-200 sm:grid-cols-2 sm:divide-x sm:divide-y-0 lg:grid-cols-4">
            <div class="flex flex-col items-start gap-3 px-0 py-6 sm:px-6 sm:py-0 lg:py-8">
                <i class="fa fa-truck text-2xl text-brand-500" aria-hidden="true"></i>
                <h5 class="font-heading text-lg font-semibold text-ink-900">Miễn phí Ship</h5>
                <span class="text-sm text-ink-500">Miễn phí Ship khu vực Việt Nam</span>
            </div>
            <div class="flex flex-col items-start gap-3 px-0 py-6 sm:px-6 sm:py-0 lg:py-8">
                <i class="fa fa-credit-card text-2xl text-brand-500" aria-hidden="true"></i>
                <h5 class="font-heading text-lg font-semibold text-ink-900">Thanh toán khi nhận hàng</h5>
                <span class="text-sm text-ink-500">Tùy chọn tiền mặt khi nhận hàng</span>
            </div>
            <div class="flex flex-col items-start gap-3 px-0 py-6 sm:px-6 sm:py-0 lg:py-8">
                <i class="fa fa-calendar text-2xl text-brand-500" aria-hidden="true"></i>
                <h5 class="font-heading text-lg font-semibold text-ink-900">Bảo hành 12 tháng</h5>
                <span class="text-sm text-ink-500">Đổi trả trong vòng 7 ngày</span>
            </div>
            <div class="flex flex-col items-start gap-3 px-0 py-6 sm:px-6 sm:py-0 lg:py-8">
                <i class="fas fa-star-of-life text-2xl text-brand-500" aria-hidden="true"></i>
                <h5 class="font-heading text-lg font-semibold text-ink-900">Hỗ trợ trực tuyến 24/7</h5>
                <span class="text-sm text-ink-500">Chúng tôi luôn sẵn sàng hỗ trợ</span>
            </div>
        </div>
    </div>
</section>
<!-- WHY US End Here -->

<!-- PHẦN SẢN PHẨM TRANG HOME -->
<section class="reveal-on-scroll bg-ink-50 py-14 md:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-wrap items-end justify-between gap-4 border-b border-ink-200 pb-6">
            <div>
                <span class="text-serif-accent text-xs font-semibold uppercase tracking-[0.2em]">Sản phẩm</span>
                <h2 class="mt-2 font-heading text-2xl font-semibold text-ink-900 sm:text-3xl">Khám phá laptop phù hợp với bạn</h2>
            </div>
            <!-- Tab nav: underline style (was: pill segmented control). Plain vanilla-JS click
                 handler (home-tab-trigger/home-tab-panel, script at the bottom of this file) —
                 Bootstrap's data-bs-toggle="tab" data-api does NOT actually fire in this theme's
                 bundled plugins.min.js, so don't rely on it. Same proven pattern as the
                 account-page tabs in view/user/myaccount.php. -->
            <div class="flex flex-wrap gap-6">
                <a class="home-tab-trigger active min-h-11 border-b-2 border-transparent py-1 text-sm font-semibold uppercase tracking-wide text-ink-500 transition-colors hover:text-brand-600 [&.active]:border-brand-600! [&.active]:text-brand-600!"
                    data-tab-target="new-arrival" href="#new-arrival"><span>Mới</span></a>
                <a class="home-tab-trigger min-h-11 border-b-2 border-transparent py-1 text-sm font-semibold uppercase tracking-wide text-ink-500 transition-colors hover:text-brand-600 [&.active]:border-brand-600! [&.active]:text-brand-600!"
                    data-tab-target="bestseller" href="#bestseller"><span>Bán chạy</span></a>
                <a class="home-tab-trigger min-h-11 border-b-2 border-transparent py-1 text-sm font-semibold uppercase tracking-wide text-ink-500 transition-colors hover:text-brand-600 [&.active]:border-brand-600! [&.active]:text-brand-600!"
                    data-tab-target="featured-products" href="#featured-products"><span>Nổi bật</span></a>
            </div>
        </div>

        <div class="tab-content">
            <div id="new-arrival" data-tab-panel class="tab-pane active hidden [&.active]:block!" role="tabpanel">
                <div class="grid grid-cols-2 gap-5 sm:grid-cols-3 lg:grid-cols-4">
                    <!-- Phần show sản phẩm mới nhất -->
                    <?php
                    foreach ($prohome as $pro) { ?>
                        <div class="card-hover group card-boutique flex flex-col overflow-hidden rounded-md">
                            <div class="relative aspect-square overflow-hidden border-b border-ink-200 bg-ink-100">
                                <a class="block h-full w-full"
                                    href="index.php?act=prodetail&idpro=<?= e($pro['id_pro']) ?>">
                                    <img class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy" decoding="async" src="admin/uploads/<?= e($pro['img_pro']) ?>"
                                        alt="<?= e($pro['name_pro']) ?>" />
                                </a>
                                <span
                                    class="absolute top-2 right-2 rounded-sm bg-ink-50 px-2 py-1 text-[11px] font-semibold uppercase tracking-wide text-brand-700">Mới</span>
                                <?php if (!isset($pro['discount']) || $pro['discount'] <= 0) { ?>
                                <?php } else { ?>
                                    <span
                                        class="absolute top-2 left-2 rounded-sm bg-accent-600 px-2 py-1 text-[11px] font-bold text-white">-<?= e($pro['discount']) ?>%</span>
                                <?php } ?>
                                <?php if ((int) $pro['stock'] <= 0) { ?>
                                    <span class="absolute bottom-2 left-2 rounded-sm bg-ink-900/80 px-2 py-1 text-[11px] font-bold text-white"><?= htmlspecialchars($pro['stock_message'] ?: 'Hết hàng') ?></span>
                                <?php } ?>
                            </div>
                            <div class="flex flex-1 flex-col p-4">
                                <h6 class="mb-2">
                                    <a class="font-heading text-base font-semibold text-ink-900 line-clamp-2 transition-colors hover:text-brand-600"
                                        href="index.php?act=prodetail&idpro=<?= e($pro['id_pro']) ?>"><?= e($pro['name_pro']) ?></a>
                                </h6>
                                <div class="mb-3 flex items-baseline gap-2">
                                    <?php if (!isset($pro['discount']) || $pro['discount'] <= 0) { ?>
                                        <span class="font-semibold text-brand-600"><?= number_format($pro['price']) ?>₫</span>
                                    <?php } else { ?>
                                        <span
                                            class="font-semibold text-brand-600"><?= number_format(($pro['price']) - (($pro['price']) * ($pro['discount']) / 100)) ?>₫</span>
                                        <span class="text-sm text-ink-500 line-through"><?= number_format($pro['price']) ?>₫</span>
                                    <?php } ?>
                                </div>
                                <?php if ((int) $pro['stock'] > 0) { ?>
                                    <form action="index.php?act=addtocart" method="post" class="mt-auto">
<?= \Codemoi\Core\Csrf::field() ?>
                                        <input type="hidden" name="id_pro" value="<?= e($pro['id_pro']) ?>">
                                        <input type="hidden" name="name_pro" value="<?= e($pro['name_pro']) ?>">
                                        <input type="hidden" name="img_pro" value="<?= e($pro['img_pro']) ?>">
                                        <input type="hidden" name="price" value="<?= e($pro['price']) ?>">
                                        <input type="submit" name="addtocart" value="Thêm vào giỏ"
                                            class="btn-boutique inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-md px-5 py-2.5 text-sm font-semibold disabled:cursor-not-allowed disabled:opacity-50">
                                    </form>
                                <?php } else { ?>
                                    <button type="button" disabled
                                        class="mt-auto inline-flex w-full items-center justify-center gap-2 rounded-md bg-ink-100 px-5 py-2.5 text-sm font-semibold text-ink-500 cursor-not-allowed">
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
                <div class="grid grid-cols-2 gap-5 sm:grid-cols-3 lg:grid-cols-4">
                    <!-- Sản phẩm bán chạy -->
                    <?php
                    foreach ($list_bestsp as $pro) { ?>
                        <div class="card-hover group card-boutique flex flex-col overflow-hidden rounded-md">
                            <div class="relative aspect-square overflow-hidden border-b border-ink-200 bg-ink-100">
                                <a class="block h-full w-full" href="index.php?act=prodetail&idpro=<?= e($pro['id_pro']) ?>">
                                    <img class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy" decoding="async" src="admin/uploads/<?= e($pro['img_pro']) ?>"
                                        alt="<?= e($pro['name_pro']) ?>" />
                                </a>
                                <span
                                    class="absolute top-2 right-2 rounded-sm bg-ink-50 px-2 py-1 text-[11px] font-semibold uppercase tracking-wide text-brand-700">Hot</span>
                                <?php if (!isset($pro['discount']) || $pro['discount'] <= 0) { ?>
                                <?php } else { ?>
                                    <span
                                        class="absolute top-2 left-2 rounded-sm bg-accent-600 px-2 py-1 text-[11px] font-bold text-white">-<?= e($pro['discount']) ?>%</span>
                                <?php } ?>
                                <?php if ((int) $pro['stock'] <= 0) { ?>
                                    <span class="absolute bottom-2 left-2 rounded-sm bg-ink-900/80 px-2 py-1 text-[11px] font-bold text-white"><?= htmlspecialchars($pro['stock_message'] ?: 'Hết hàng') ?></span>
                                <?php } ?>
                            </div>
                            <div class="flex flex-1 flex-col p-4">
                                <h6 class="mb-2">
                                    <a class="font-heading text-base font-semibold text-ink-900 line-clamp-2 transition-colors hover:text-brand-600"
                                        href="index.php?act=prodetail&idpro=<?= e($pro['id_pro']) ?>"><?= e($pro['name_pro']) ?></a>
                                </h6>
                                <div class="mb-3 flex items-baseline gap-2">
                                    <?php if (!isset($pro['discount']) || $pro['discount'] <= 0) { ?>
                                        <span class="font-semibold text-brand-600"><?= number_format($pro['price']) ?>₫</span>
                                    <?php } else { ?>
                                        <span
                                            class="font-semibold text-brand-600"><?= number_format(($pro['price']) - (($pro['price']) * ($pro['discount']) / 100)) ?>₫</span>
                                        <span class="text-sm text-ink-500 line-through"><?= number_format($pro['price']) ?>₫</span>
                                    <?php } ?>
                                </div>
                                <?php if ((int) $pro['stock'] > 0) { ?>
                                    <form action="index.php?act=addtocart" method="post" class="mt-auto">
<?= \Codemoi\Core\Csrf::field() ?>
                                        <input type="hidden" name="id_pro" value="<?= e($pro['id_pro']) ?>">
                                        <input type="hidden" name="name_pro" value="<?= e($pro['name_pro']) ?>">
                                        <input type="hidden" name="img_pro" value="<?= e($pro['img_pro']) ?>">
                                        <input type="hidden" name="price" value="<?= e($pro['price']) ?>">
                                        <input type="submit" name="addtocart" value="Thêm vào giỏ"
                                            class="btn-boutique inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-md px-5 py-2.5 text-sm font-semibold disabled:cursor-not-allowed disabled:opacity-50">
                                    </form>
                                <?php } else { ?>
                                    <button type="button" disabled
                                        class="mt-auto inline-flex w-full items-center justify-center gap-2 rounded-md bg-ink-100 px-5 py-2.5 text-sm font-semibold text-ink-500 cursor-not-allowed">
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
                <div class="grid grid-cols-2 gap-5 sm:grid-cols-3 lg:grid-cols-4">
                    <!-- Phần show sản phẩm nổi bật -->
                    <?php
                    foreach ($list_topsp as $pro) { ?>
                        <div class="card-hover group card-boutique flex flex-col overflow-hidden rounded-md">
                            <div class="relative aspect-square overflow-hidden border-b border-ink-200 bg-ink-100">
                                <a class="block h-full w-full" href="index.php?act=prodetail&idpro=<?= e($pro['id_pro']) ?>">
                                    <img class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy" decoding="async" src="admin/uploads/<?= e($pro['img_pro']) ?>"
                                        alt="<?= e($pro['name_pro']) ?>" />
                                </a>
                                <span
                                    class="absolute top-2 right-2 rounded-sm bg-ink-50 px-2 py-1 text-[11px] font-semibold uppercase tracking-wide text-brand-700">Nổi bật</span>
                                <?php if (!isset($pro['discount']) || $pro['discount'] <= 0) { ?>
                                <?php } else { ?>
                                    <span
                                        class="absolute top-2 left-2 rounded-sm bg-accent-600 px-2 py-1 text-[11px] font-bold text-white">-<?= e($pro['discount']) ?>%</span>
                                <?php } ?>
                                <?php if ((int) $pro['stock'] <= 0) { ?>
                                    <span class="absolute bottom-2 left-2 rounded-sm bg-ink-900/80 px-2 py-1 text-[11px] font-bold text-white"><?= htmlspecialchars($pro['stock_message'] ?: 'Hết hàng') ?></span>
                                <?php } ?>
                            </div>
                            <div class="flex flex-1 flex-col p-4">
                                <h6 class="mb-2">
                                    <a class="font-heading text-base font-semibold text-ink-900 line-clamp-2 transition-colors hover:text-brand-600"
                                        href="index.php?act=prodetail&idpro=<?= e($pro['id_pro']) ?>"><?= e($pro['name_pro']) ?></a>
                                </h6>
                                <div class="mb-3 flex items-baseline gap-2">
                                    <?php if (!isset($pro['discount']) || $pro['discount'] <= 0) { ?>
                                        <span class="font-semibold text-brand-600"><?= number_format($pro['price']) ?>₫</span>
                                    <?php } else { ?>
                                        <span
                                            class="font-semibold text-brand-600"><?= number_format(($pro['price']) - (($pro['price']) * ($pro['discount']) / 100)) ?>₫</span>
                                        <span class="text-sm text-ink-500 line-through"><?= number_format($pro['price']) ?>₫</span>
                                    <?php } ?>
                                </div>
                                <?php if ((int) $pro['stock'] > 0) { ?>
                                    <form action="index.php?act=addtocart" method="post" class="mt-auto">
<?= \Codemoi\Core\Csrf::field() ?>
                                        <input type="hidden" name="id_pro" value="<?= e($pro['id_pro']) ?>">
                                        <input type="hidden" name="name_pro" value="<?= e($pro['name_pro']) ?>">
                                        <input type="hidden" name="img_pro" value="<?= e($pro['img_pro']) ?>">
                                        <input type="hidden" name="price" value="<?= e($pro['price']) ?>">
                                        <input type="submit" name="addtocart" value="Thêm vào giỏ"
                                            class="btn-boutique inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-md px-5 py-2.5 text-sm font-semibold disabled:cursor-not-allowed disabled:opacity-50">
                                    </form>
                                <?php } else { ?>
                                    <button type="button" disabled
                                        class="mt-auto inline-flex w-full items-center justify-center gap-2 rounded-md bg-ink-100 px-5 py-2.5 text-sm font-semibold text-ink-500 cursor-not-allowed">
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

<!-- PC Gaming spotlight: framed split layout (was: full-bleed overlay card + glow-pulse blob) -->
<section class="reveal-on-scroll mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8 md:py-20">
    <div class="grid grid-cols-1 items-center gap-8 lg:grid-cols-2 lg:gap-12">
        <div class="card-boutique overflow-hidden rounded-lg lg:order-2">
            <img src="./assets/images/banner/pcgami.png" alt="PC Gaming Turbotech" class="h-64 w-full object-cover sm:h-80 lg:h-96" loading="lazy" decoding="async" />
        </div>
        <div class="lg:order-1">
            <span class="text-serif-accent text-xs font-semibold uppercase tracking-[0.2em]">PC Gaming</span>
            <h2 class="mt-3 font-heading text-2xl font-semibold text-ink-900 sm:text-3xl">Sức mạnh không giới hạn</h2>
            <span class="mt-4 block h-px w-16 bg-brand-500"></span>
            <p class="mt-5 text-sm leading-relaxed text-ink-600 sm:text-base">
                Cung cấp giải pháp PC và Laptop chính hãng, cấu hình tùy biến mạnh mẽ theo nhu cầu thực tế. Cam kết chất
                lượng dịch vụ, bảo hành dài hạn và hỗ trợ kỹ thuật tận tâm.
            </p>
            <div class="mt-6">
                <a class="btn-boutique inline-flex items-center justify-center gap-2 rounded-md px-6 py-3 text-sm font-semibold"
                    href="index.php?act=product&idcate=8">Mua ngay <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
            </div>
        </div>
    </div>
</section>
<!-- PC Gaming spotlight End Here -->

<!-- Brand banners: framed image + caption below (was: image with dark gradient text-overlay) -->
<section class="reveal-on-scroll mx-auto max-w-7xl px-4 pb-14 sm:px-6 lg:px-8 md:pb-20">
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <a href="index.php?act=product&idcate=12" class="card-hover card-boutique group block overflow-hidden rounded-md">
            <img class="h-56 w-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy" decoding="async" src="./assets/images/banner/lapmsi.jpg" alt="Turbotech" />
            <div class="flex items-center justify-between border-t border-ink-200 p-4">
                <span class="font-heading text-lg font-semibold text-ink-900">MSI Series</span>
                <i class="fa-solid fa-arrow-right text-brand-500" aria-hidden="true"></i>
            </div>
        </a>
        <a href="index.php?act=product&idcate=11" class="card-hover card-boutique group block overflow-hidden rounded-md">
            <img class="h-56 w-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy" decoding="async" src="./assets/images/banner/lenovo.jpg" alt="Turbotech" />
            <div class="flex items-center justify-between border-t border-ink-200 p-4">
                <span class="font-heading text-lg font-semibold text-ink-900">Lenovo Series</span>
                <i class="fa-solid fa-arrow-right text-brand-500" aria-hidden="true"></i>
            </div>
        </a>
    </div>
</section>
<!-- Brand banners End Here -->

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
