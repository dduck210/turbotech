<?php
/**
 * @var array $prohome    Full product listing (Codemoi\Model\Product::allHome()).
 * @var array $list_topsp Featured products (Codemoi\Model\Product::featured()).
 * @var array $list_bestsp Best-selling products (Codemoi\Model\Product::bestSellers()).
 */
?>
<!-- HERO BANNER (was: slick .main-slider carousel -> now a static responsive grid, no JS needed) -->
<section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <!-- Begin Hero Banner Item -->
        <div class="relative flex min-h-[280px] items-end overflow-hidden rounded-2xl bg-ink-900 bg-cover bg-center sm:min-h-[380px]"
            style="background-image:url('./src/image/slider/anhmoi.jpg')">
            <div class="relative z-10 p-6 sm:p-8">
                <a class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2"
                    href="index.php?act=product">Mua ngay</a>
            </div>
        </div>
        <!-- Hero Banner Item End Here -->
        <!-- Begin Hero Banner Item -->
        <div class="relative flex min-h-[280px] items-end overflow-hidden rounded-2xl bg-ink-900 bg-cover bg-center sm:min-h-[380px]"
            style="background-image:url('./src/image/slider/anhmoi2.jpg')">
            <div class="relative z-10 p-6 sm:p-8">
                <a class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2"
                    href="index.php?act=product">Mua ngay</a>
            </div>
        </div>
        <!-- Hero Banner Item End Here -->
    </div>
</section>
<!-- HERO BANNER End Here -->

<!-- FREE SHIP -->
<section class="border-y border-ink-200 bg-white py-12 md:py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 gap-6 sm:grid-cols-4">
            <!-- Begin Shipping Information Item -->
            <div class="flex flex-col items-center gap-3 text-center">
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-brand-50 text-2xl text-brand-600">
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
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-brand-50 text-2xl text-brand-600">
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
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-brand-50 text-2xl text-brand-600">
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
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-brand-50 text-2xl text-brand-600">
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
        <!-- Tab nav (Bootstrap Tab JS from plugins.min.js still drives switching via data-bs-toggle="tab";
             active-state colors respond purely to the ".active" class Bootstrap toggles, via the [&.active] variant below) -->
        <div class="mb-8 flex flex-wrap justify-center gap-3">
            <a class="active inline-flex min-h-11 items-center justify-center rounded-full border border-ink-200 bg-white px-4 py-2.5 text-sm font-semibold text-ink-700 transition-colors hover:bg-ink-50 focus:outline-none focus:ring-2 focus:ring-brand-500 [&.active]:!border-brand-600 [&.active]:!bg-brand-600 [&.active]:!text-white"
                data-bs-toggle="tab" href="#new-arrival"><span>Sản phẩm mới</span></a>
            <a class="inline-flex min-h-11 items-center justify-center rounded-full border border-ink-200 bg-white px-4 py-2.5 text-sm font-semibold text-ink-700 transition-colors hover:bg-ink-50 focus:outline-none focus:ring-2 focus:ring-brand-500 [&.active]:!border-brand-600 [&.active]:!bg-brand-600 [&.active]:!text-white"
                data-bs-toggle="tab" href="#bestseller"><span>Sản phẩm bán chạy</span></a>
            <a class="inline-flex min-h-11 items-center justify-center rounded-full border border-ink-200 bg-white px-4 py-2.5 text-sm font-semibold text-ink-700 transition-colors hover:bg-ink-50 focus:outline-none focus:ring-2 focus:ring-brand-500 [&.active]:!border-brand-600 [&.active]:!bg-brand-600 [&.active]:!text-white"
                data-bs-toggle="tab" href="#featured-products"><span>Sản phẩm nổi bật</span></a>
        </div>

        <div class="tab-content">
            <div id="new-arrival" class="tab-pane active hidden [&.active]:!block" role="tabpanel">
                <div class="grid grid-cols-2 gap-6 sm:grid-cols-3 lg:grid-cols-4">
                    <!-- Phần show sản phẩm mới nhất -->
                    <?php
                    foreach ($prohome as $pro) { ?>
                    <div class="group relative overflow-hidden rounded-2xl border border-ink-200 bg-white shadow-sm transition-shadow hover:shadow-md">
                        <div class="relative aspect-square overflow-hidden bg-ink-100">
                            <a class="block h-full w-full"
                                href="index.php?act=prodetail&idpro=<?php echo $pro['id_pro'] ?>">
                                <img class="h-full w-full object-cover" src="admin/uploads/<?php echo $pro['img_pro'] ?>"
                                    alt="<?php echo $pro['name_pro'] ?>" />
                            </a>
                            <span
                                class="absolute top-2 right-2 inline-flex items-center rounded-full bg-brand-50 px-3 py-1 text-xs font-medium text-brand-700">Mới</span>
                            <?php if (!isset($pro['discount']) || $pro['discount'] <= 0) { ?>
                            <?php } else { ?>
                            <span
                                class="absolute top-2 left-2 rounded-full bg-red-600 px-2 py-1 text-xs font-bold text-white">-<?= $pro['discount'] ?>%</span>
                            <?php } ?>
                        </div>
                        <div class="p-4">
                            <h6 class="mb-2">
                                <a class="font-heading font-semibold text-ink-900 line-clamp-2 transition-colors hover:text-brand-600"
                                    href="index.php?act=prodetail&idpro=<?php echo $pro['id_pro'] ?>"><?php echo $pro['name_pro'] ?></a>
                            </h6>
                            <div class="mb-3 flex items-baseline gap-2">
                                <?php if (!isset($pro['discount']) || $pro['discount'] <= 0) { ?>
                                <span class="font-bold text-brand-600"><?= number_format($pro['price']) ?>₫</span>
                                <?php } else { ?>
                                <span
                                    class="font-bold text-brand-600"><?= number_format(($pro['price']) - (($pro['price']) * ($pro['discount']) / 100)) ?>₫</span>
                                <span class="text-sm text-ink-300 line-through"><?= number_format($pro['price']) ?>₫</span>
                                <?php } ?>
                            </div>
                            <form action="index.php?act=addtocart" method="post">
                                <input type="hidden" name="id_pro" value="<?php echo $pro['id_pro'] ?>">
                                <input type="hidden" name="name_pro" value="<?php echo $pro['name_pro'] ?>">
                                <input type="hidden" name="img_pro" value="<?php echo $pro['img_pro'] ?>">
                                <input type="hidden" name="price" value="<?php echo $pro['price'] ?>">
                                <input type="submit" name="addtocart" value="Thêm vào giỏ"
                                    class="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                            </form>
                        </div>
                    </div>
                    <?php   } ?>
                    <!-- end phần show sản sản phẩm mới nhất -->
                </div>
            </div>
            <div id="bestseller" class="tab-pane hidden [&.active]:!block" role="tabpanel">
                <div class="grid grid-cols-2 gap-6 sm:grid-cols-3 lg:grid-cols-4">
                    <!-- Sản phẩm bán chạy -->
                    <?php
                    foreach ($list_bestsp as $pro) { ?>
                    <div class="group relative overflow-hidden rounded-2xl border border-ink-200 bg-white shadow-sm transition-shadow hover:shadow-md">
                        <div class="relative aspect-square overflow-hidden bg-ink-100">
                            <a class="block h-full w-full" href="index.php?act=prodetail&idpro=<?= $pro['id_pro'] ?>">
                                <img class="h-full w-full object-cover" src="admin/uploads/<?= $pro['img_pro'] ?>"
                                    alt="<?= $pro['name_pro'] ?>" />
                            </a>
                            <span
                                class="absolute top-2 right-2 inline-flex items-center rounded-full bg-brand-50 px-3 py-1 text-xs font-medium text-brand-700">Hot</span>
                            <?php if (!isset($pro['discount']) || $pro['discount'] <= 0) { ?>
                            <?php } else { ?>
                            <span
                                class="absolute top-2 left-2 rounded-full bg-red-600 px-2 py-1 text-xs font-bold text-white">-<?= $pro['discount'] ?>%</span>
                            <?php } ?>
                        </div>
                        <div class="p-4">
                            <h6 class="mb-2">
                                <a class="font-heading font-semibold text-ink-900 line-clamp-2 transition-colors hover:text-brand-600"
                                    href="index.php?act=prodetail&idpro=<?= $pro['id_pro'] ?>"><?= $pro['name_pro'] ?></a>
                            </h6>
                            <div class="mb-3 flex items-baseline gap-2">
                                <?php if (!isset($pro['discount']) || $pro['discount'] <= 0) { ?>
                                <span class="font-bold text-brand-600"><?= number_format($pro['price']) ?>₫</span>
                                <?php } else { ?>
                                <span
                                    class="font-bold text-brand-600"><?= number_format(($pro['price']) - (($pro['price']) * ($pro['discount']) / 100)) ?>₫</span>
                                <span class="text-sm text-ink-300 line-through"><?= number_format($pro['price']) ?>₫</span>
                                <?php } ?>
                            </div>
                            <form action="index.php?act=addtocart" method="post">
                                <input type="hidden" name="id_pro" value="<?php echo $pro['id_pro'] ?>">
                                <input type="hidden" name="name_pro" value="<?php echo $pro['name_pro'] ?>">
                                <input type="hidden" name="img_pro" value="<?php echo $pro['img_pro'] ?>">
                                <input type="hidden" name="price" value="<?php echo $pro['price'] ?>">
                                <input type="submit" name="addtocart" value="Thêm vào giỏ"
                                    class="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                            </form>
                        </div>
                    </div>
                    <?php } ?>
                    <!-- End sản phẩm bán chạy -->
                </div>
            </div>

            <!-- show sản phẩm nổi bật -->
            <div id="featured-products" class="tab-pane hidden [&.active]:!block" role="tabpanel">
                <div class="grid grid-cols-2 gap-6 sm:grid-cols-3 lg:grid-cols-4">
                    <!-- Phần show sản phẩm nổi bật -->
                    <?php
                    foreach ($list_topsp as $pro) { ?>
                    <div class="group relative overflow-hidden rounded-2xl border border-ink-200 bg-white shadow-sm transition-shadow hover:shadow-md">
                        <div class="relative aspect-square overflow-hidden bg-ink-100">
                            <a class="block h-full w-full" href="index.php?act=prodetail&idpro=<?= $pro['id_pro'] ?>">
                                <img class="h-full w-full object-cover" src="admin/uploads/<?= $pro['img_pro'] ?>"
                                    alt="<?= $pro['name_pro'] ?>" />
                            </a>
                            <span
                                class="absolute top-2 right-2 inline-flex items-center rounded-full bg-brand-50 px-3 py-1 text-xs font-medium text-brand-700">Nổi
                                bật</span>
                            <?php if (!isset($pro['discount']) || $pro['discount'] <= 0) { ?>
                            <?php } else { ?>
                            <span
                                class="absolute top-2 left-2 rounded-full bg-red-600 px-2 py-1 text-xs font-bold text-white">-<?= $pro['discount'] ?>%</span>
                            <?php } ?>
                        </div>
                        <div class="p-4">
                            <h6 class="mb-2">
                                <a class="font-heading font-semibold text-ink-900 line-clamp-2 transition-colors hover:text-brand-600"
                                    href="index.php?act=prodetail&idpro=<?= $pro['id_pro'] ?>"><?= $pro['name_pro'] ?></a>
                            </h6>
                            <div class="mb-3 flex items-baseline gap-2">
                                <?php if (!isset($pro['discount']) || $pro['discount'] <= 0) { ?>
                                <span class="font-bold text-brand-600"><?= number_format($pro['price']) ?>₫</span>
                                <?php } else { ?>
                                <span
                                    class="font-bold text-brand-600"><?= number_format(($pro['price']) - (($pro['price']) * ($pro['discount']) / 100)) ?>₫</span>
                                <span class="text-sm text-ink-300 line-through"><?= number_format($pro['price']) ?>₫</span>
                                <?php } ?>
                            </div>
                            <form action="index.php?act=addtocart" method="post">
                                <input type="hidden" name="id_pro" value="<?php echo $pro['id_pro'] ?>">
                                <input type="hidden" name="name_pro" value="<?php echo $pro['name_pro'] ?>">
                                <input type="hidden" name="img_pro" value="<?php echo $pro['img_pro'] ?>">
                                <input type="hidden" name="price" value="<?php echo $pro['price'] ?>">
                                <input type="submit" name="addtocart" value="Thêm vào giỏ"
                                    class="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                            </form>
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
    <div class="relative flex min-h-[280px] items-center overflow-hidden rounded-2xl bg-ink-900 bg-cover bg-center sm:min-h-[420px]"
        style="background-image:url('./src/image/banner/pcgami.png')">
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
                <img class="h-56 w-full object-cover" src="./src/image/banner/lapmsi.jpg" alt="Tubotech" />
            </a>
        </div>
        <div class="overflow-hidden rounded-2xl border border-ink-200 shadow-sm transition-shadow hover:shadow-md">
            <a href="index.php?act=product&idcate=11">
                <img class="h-56 w-full object-cover" src="./src/image/banner/lenovo.jpg" alt="Tubotech" />
            </a>
        </div>
    </div>
</section>
<!-- Tubotech Product With Two Columns Area End Here -->