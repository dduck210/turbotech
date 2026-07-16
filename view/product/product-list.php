<?php

/**
 * @var array  $listpro   Filtered product listing (Codemoi\Model\Product::search()).
 * @var string $namecate  Selected category/brand name (Codemoi\Model\Category::name()).
 * @var array  $listcate  All categories/brands (Codemoi\Model\Category::all()).
 * @var array  $list_topsp Featured products for the sidebar (Codemoi\Model\Product::featured()).
 */
?>
<!-- Breadcrumb -->
<div class="border-b border-ink-200 px-4 py-4 sm:px-6 lg:px-8">
    <nav aria-label="Breadcrumb" class="mx-auto max-w-7xl text-sm text-ink-500">
        <ol class="flex items-center gap-2">
            <li><a href="index.php" class="hover:text-brand-600 transition-colors">Trang chủ</a></li>
            <li aria-hidden="true">/</li>
            <li><a href="index.php?act=product" class="font-medium text-brand-600">Sản phẩm</a></li>
        </ol>
    </nav>
</div>
<!-- End Breadcrumb -->

<!-- Content Wrapper: sidebar filter panel (category quick-links + keyword/price form combined,
     was: separate category nav + a whole extra filter bar above the grid) + main product grid.
     Combined filter form field names (kyw, idcate, min_price, max_price), value-repopulation
     logic and htmlspecialchars() escaping preserved byte-for-byte. -->
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10 md:py-14">
    <div class="grid grid-cols-1 lg:grid-cols-[300px_1fr] gap-10">

        <!-- Sidebar: mobile shows after product grid (order-2), desktop shows first (lg:order-1) -->
        <aside class="order-2 lg:order-1 space-y-8">

            <form action="index.php" method="get">
                <input type="hidden" name="act" value="product">

                <!-- Categories -->
                <div>
                    <h5 class="font-heading text-lg font-semibold text-ink-900">Danh mục</h5>
                    <span class="mt-2 block h-px w-10 bg-brand-500"></span>
                    <ul class="mt-4 space-y-1">
                        <?php foreach ($listcate as $cate) {
                            extract($cate);
                            $isActiveCate = (isset($_GET['idcate']) && ($_GET['idcate']) == $id_cate);
                            $linkpro = "index.php?act=product&idcate=" . e($id_cate);
                            $cateLinkClass = $isActiveCate
                                ? 'flex items-center gap-2 border-l-2 border-brand-500 bg-brand-50 px-3 py-2 text-sm font-semibold text-brand-700'
                                : 'flex items-center gap-2 border-l-2 border-transparent px-3 py-2 text-sm text-ink-700 hover:border-ink-300 hover:text-brand-600 transition-colors';
                        ?>
                            <li>
                                <a href="<?= e($linkpro) ?>" class="<?= e($cateLinkClass) ?>">
                                    <i class="fa-sharp fa-solid fa-angles-right text-xs"></i> <?= e($name_cate) ?>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>

                <!-- Keyword + price filters -->
                <div class="mt-8 border-t border-ink-200 pt-6">
                    <h5 class="font-heading text-lg font-semibold text-ink-900">Bộ lọc</h5>
                    <span class="mt-2 block h-px w-10 bg-brand-500"></span>
                    <div class="mt-4 space-y-4">
                        <div>
                            <label for="kyw" class="block text-sm font-medium text-ink-700 mb-1.5">Từ khóa</label>
                            <input type="text" id="kyw" name="kyw" placeholder="Nhập tên sản phẩm..."
                                value="<?= isset($_GET['kyw']) ? htmlspecialchars($_GET['kyw']) : '' ?>"
                                class="block w-full rounded-md border border-ink-300 bg-ink-50 px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-500 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                        </div>
                        <div>
                            <label for="min_price" class="block text-sm font-medium text-ink-700 mb-1.5">Giá từ</label>
                            <input type="number" id="min_price" name="min_price" min="0" placeholder="Giá từ"
                                value="<?= isset($_GET['min_price']) ? htmlspecialchars($_GET['min_price']) : '' ?>"
                                class="block w-full rounded-md border border-ink-300 bg-ink-50 px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-500 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                        </div>
                        <div>
                            <label for="max_price" class="block text-sm font-medium text-ink-700 mb-1.5">Đến giá</label>
                            <input type="number" id="max_price" name="max_price" min="0" placeholder="Đến giá"
                                value="<?= isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : '' ?>"
                                class="block w-full rounded-md border border-ink-300 bg-ink-50 px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-500 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                        </div>
                        <!-- idcate stays whatever the category link above set via the querystring;
                             re-submitted here as a hidden field so keyword/price filters don't
                             reset the active category. -->
                        <input type="hidden" name="idcate" value="<?= isset($_GET['idcate']) ? e($_GET['idcate']) : '0' ?>">
                        <input type="submit" name="btn-search" value="Tìm kiếm"
                            class="btn-boutique inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-md px-5 py-2.5 text-sm font-semibold">
                    </div>
                </div>
            </form>

            <!-- Recommended products (was slick slider "jb-list-product_slider" — dropped per
                 design-system's legacy-plugin removal guidance, replaced with a plain vertical list) -->
            <div class="border-t border-ink-200 pt-6">
                <h4 class="font-heading text-lg font-semibold text-ink-900">Được đề xuất</h4>
                <span class="mt-2 block h-px w-10 bg-brand-500"></span>
                <div class="mt-4 space-y-4">
                    <?php foreach ($list_topsp as $topsp) { ?>
                        <div class="flex gap-3">
                            <a href="index.php?act=prodetail&idpro=<?= e($topsp['id_pro']) ?>"
                                class="shrink-0 h-16 w-16 rounded-md border border-ink-200 bg-ink-100 overflow-hidden">
                                <img src="admin/uploads/<?= e($topsp['img_pro']) ?>"
                                    alt="Ảnh sản phẩm <?= e($topsp['name_pro']) ?>"
                                    class="h-full w-full object-cover" loading="lazy" decoding="async">
                            </a>
                            <div class="min-w-0">
                                <h6 class="text-sm font-medium text-ink-900 line-clamp-2">
                                    <a href="index.php?act=prodetail&idpro=<?= e($topsp['id_pro']) ?>"
                                        class="hover:text-brand-600 transition-colors"><?= e($topsp['name_pro']) ?></a>
                                </h6>
                                <div class="mt-1">
                                    <?php if ($topsp['discount'] <= 0) { ?>
                                        <span class="text-brand-600 font-semibold text-sm"><?= number_format($topsp['price']) ?>₫</span>
                                    <?php } else { ?>
                                        <span class="text-brand-600 font-semibold text-sm"><?= number_format(($topsp['price']) - (($topsp['price']) * ($topsp['discount']) / 100)) ?>₫</span>
                                        <span class="text-ink-500 line-through text-xs ml-1"><?= number_format($topsp['price']) ?>₫</span>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </aside>
        <!-- End Sidebar -->

        <!-- Main content -->
        <div class="order-1 lg:order-2">

            <!-- Top banner -->
            <div class="rounded-md border border-ink-300 overflow-hidden mb-8">
                <a href="#">
                    <img src="./assets/images/shop/anhmoi1.png" alt="Ultraphone Product"
                        class="w-full h-[220px] md:h-[320px] object-cover">
                </a>
            </div>

            <!-- Result count -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6 border-b border-ink-200 pb-4">
                <p class="text-sm text-ink-500">
                    Hiện có <span class="font-semibold text-ink-900"><?php echo count($listpro); ?></span> sản phẩm
                    <?php if (!empty($namecate)) { ?> trong <span class="font-semibold text-ink-900"><?= e($namecate) ?></span><?php } ?>.
                </p>
            </div>

            <!-- Empty state: same empty($listpro) trigger condition, restyled per design-system recipe -->
            <?php if (empty($listpro)) { ?>
                <div class="card-boutique rounded-md py-16 text-center mb-8">
                    <i class="fa-solid fa-face-sad-tear text-5xl text-ink-400" aria-hidden="true"></i>
                    <h1 class="mt-4 font-heading text-xl font-semibold text-ink-900">Không tìm thấy sản phẩm phù hợp</h1>
                    <p class="mt-2 text-sm text-ink-500 max-w-md mx-auto">
                        Không tồn tại sản phẩm trùng khớp với từ khóa bạn nhập, vui lòng tìm kiếm sản phẩm khác.
                    </p>
                </div>
            <?php } ?>

            <!-- Product grid (boutique product-card recipe: framed image, serif name, quiet hover) -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-5 md:gap-6">
                <?php foreach ($listpro as $pro) { ?>
                    <div class="card-hover card-boutique flex flex-col rounded-md overflow-hidden">
                        <div class="relative aspect-square bg-ink-100 overflow-hidden border-b border-ink-200">
                            <a href="index.php?act=prodetail&idpro=<?= e($pro['id_pro']) ?>">
                                <img src="admin/uploads/<?= e($pro['img_pro']) ?>"
                                    alt="Ảnh sản phẩm <?= e($pro['name_pro']) ?>"
                                    class="h-full w-full object-cover" loading="lazy" decoding="async">
                            </a>
                            <?php if ($pro['discount'] > 0) { ?>
                                <span class="absolute top-2 left-2 rounded-sm bg-accent-600 text-white text-[11px] font-bold px-2 py-1">
                                    -<?= e($pro['discount']) ?>%
                                </span>
                            <?php } ?>
                            <?php if ((int) $pro['stock'] <= 0) { ?>
                                <span class="absolute top-2 right-2 rounded-sm bg-ink-900/80 text-white text-[11px] font-bold px-2 py-1">
                                    <?= e($pro['stock_message'] ?: 'Hết hàng') ?>
                                </span>
                            <?php } ?>
                        </div>
                        <div class="flex flex-1 flex-col p-4">
                            <h6 class="font-heading font-semibold text-ink-900 line-clamp-2 mb-2">
                                <a href="index.php?act=prodetail&idpro=<?= e($pro['id_pro']) ?>"
                                    class="hover:text-brand-600 transition-colors">
                                    <?= e($pro['name_pro']) ?>
                                </a>
                            </h6>
                            <div class="mb-3">
                                <?php if ($pro['discount'] <= 0) { ?>
                                    <span class="text-brand-600 font-semibold">
                                        <?= number_format($pro['price']) ?>₫
                                    </span>
                                <?php } else { ?>
                                    <span class="text-brand-600 font-semibold">
                                        <?= number_format(($pro['price']) - (($pro['price']) * ($pro['discount']) / 100)) ?>₫
                                    </span>
                                    <span class="text-ink-500 line-through text-sm ml-1">
                                        <?= number_format($pro['price']) ?>₫
                                    </span>
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
                                        class="btn-boutique w-full inline-flex items-center justify-center gap-2 rounded-md px-5 py-2.5 text-sm font-semibold cursor-pointer">
                                </form>
                            <?php } else { ?>
                                <button type="button" disabled
                                    class="mt-auto w-full inline-flex items-center justify-center gap-2 rounded-md bg-ink-100 px-5 py-2.5 text-sm font-semibold text-ink-500 cursor-not-allowed">
                                    <?= htmlspecialchars($pro['stock_message'] ?: 'Hết hàng') ?>
                                </button>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <!-- End Main content -->
    </div>
</div>
<!-- End Content Wrapper -->
