<?php

/**
 * @var array  $listpro   Filtered product listing (Codemoi\Model\Product::search()).
 * @var string $namecate  Selected category/brand name (Codemoi\Model\Category::name()).
 * @var array  $listcate  All categories/brands (Codemoi\Model\Category::all()).
 * @var array  $list_topsp Featured products for the sidebar (Codemoi\Model\Product::featured()).
 */
?>
<!-- Breadcrumb -->
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-4">
    <nav aria-label="Breadcrumb" class="text-sm text-ink-500">
        <ol class="flex items-center gap-2">
            <li><a href="index.php" class="hover:text-brand-600 transition-colors">Trang chủ</a></li>
            <li aria-hidden="true">/</li>
            <li><a href="index.php?act=product" class="font-medium text-brand-600">Sản phẩm</a></li>
        </ol>
    </nav>
</div>
<!-- End Breadcrumb -->

<!-- Content Wrapper: sidebar (categories + recommended) + main (filter + product grid) -->
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12 md:py-16">
    <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8">

        <!-- Sidebar: mobile shows after product grid (order-2), desktop shows first (lg:order-1) -->
        <aside class="order-2 lg:order-1 space-y-6">

            <!-- Categories -->
            <div class="rounded-2xl border border-ink-200 bg-white p-5">
                <h5 class="font-heading font-semibold text-ink-900 mb-4">Danh mục</h5>
                <ul class="space-y-1">
                    <?php foreach ($listcate as $cate) {
                        extract($cate);
                        $isActiveCate = (isset($_GET['idcate']) && ($_GET['idcate']) == $id_cate);
                        $linkpro = "index.php?act=product&idcate=" . e($id_cate);
                        $cateLinkClass = $isActiveCate
                            ? 'flex items-center gap-2 rounded-lg bg-brand-50 px-3 py-2 text-sm font-semibold text-brand-700'
                            : 'flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-ink-700 hover:bg-ink-50 hover:text-brand-600 transition-colors';
                    ?>
                        <li>
                            <a href="<?= e($linkpro) ?>" class="<?= e($cateLinkClass) ?>">
                                <i class="fa-sharp fa-solid fa-angles-right text-xs"></i> <?= e($name_cate) ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>

            <!-- Sidebar banner -->
            <div class="rounded-2xl overflow-hidden border border-ink-200">
                <a href="#">
                    <img src="./assets/images/shop/1.png" alt="Turbotech" class="w-full h-auto">
                </a>
            </div>

            <!-- Recommended products (was slick slider "jb-list-product_slider" — dropped per
                 design-system's legacy-plugin removal guidance, replaced with a plain vertical list) -->
            <div class="rounded-2xl border border-ink-200 bg-white p-5">
                <h4 class="font-heading font-semibold text-ink-900 mb-4">Được đề xuất</h4>
                <div class="space-y-4">
                    <?php foreach ($list_topsp as $topsp) { ?>
                        <div class="flex gap-3">
                            <a href="index.php?act=prodetail&idpro=<?= e($topsp['id_pro']) ?>"
                                class="shrink-0 h-16 w-16 rounded-lg bg-ink-100 overflow-hidden">
                                <img src="admin/uploads/<?= e($topsp['img_pro']) ?>"
                                    alt="Ảnh sản phẩm <?= e($topsp['name_pro']) ?>"
                                    class="h-full w-full object-cover">
                            </a>
                            <div class="min-w-0">
                                <h6 class="text-sm font-medium text-ink-900 line-clamp-2">
                                    <a href="index.php?act=prodetail&idpro=<?= e($topsp['id_pro']) ?>"
                                        class="hover:text-brand-600 transition-colors"><?= e($topsp['name_pro']) ?></a>
                                </h6>
                                <div class="mt-1">
                                    <?php if ($topsp['discount'] <= 0) { ?>
                                        <span class="text-brand-600 font-bold text-sm"><?= number_format($topsp['price']) ?>₫</span>
                                    <?php } else { ?>
                                        <span class="text-brand-600 font-bold text-sm"><?= number_format(($topsp['price']) - (($topsp['price']) * ($topsp['discount']) / 100)) ?>₫</span>
                                        <span class="text-ink-300 line-through text-xs ml-1"><?= number_format($topsp['price']) ?>₫</span>
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
            <div class="rounded-2xl overflow-hidden border border-ink-200 mb-8">
                <a href="#">
                    <img src="./assets/images/shop/anhmoi1.png" alt="Ultraphone Product"
                        class="w-full h-[220px] md:h-[350px] object-cover">
                </a>
            </div>

            <!-- Result count + filter bar
                 Combined filter form: exact field names (kyw, idcate, min_price, max_price),
                 value-repopulation logic and htmlspecialchars() escaping preserved byte-for-byte.
                 Grid/list view toggle (Bootstrap tabs "grid-view"/"list-view") DROPPED per
                 instructions — simplified to a single responsive grid (documented in report). -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
                <p class="text-sm text-ink-500">
                    Hiện có <span class="font-semibold text-ink-900"><?php echo count($listpro); ?></span> sản phẩm.
                </p>
            </div>

            <form action="index.php" method="get" class="rounded-2xl border border-ink-200 bg-white p-5 mb-8">
                <input type="hidden" name="act" value="product">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                    <div>
                        <label for="kyw" class="block text-sm font-medium text-ink-700 mb-1.5">Từ khóa</label>
                        <input type="text" id="kyw" name="kyw" placeholder="Nhập tên sản phẩm..."
                            value="<?= isset($_GET['kyw']) ? htmlspecialchars($_GET['kyw']) : '' ?>"
                            class="block w-full rounded-lg border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    </div>
                    <div>
                        <label for="idcate" class="block text-sm font-medium text-ink-700 mb-1.5">Thương hiệu</label>
                        <select id="idcate" name="idcate"
                            class="block w-full rounded-lg border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                            <option value="0">Tất cả thương hiệu</option>
                            <?php foreach ($listcate as $cate) { ?>
                                <option value="<?= e($cate['id_cate']) ?>"
                                    <?= (isset($_GET['idcate']) && $_GET['idcate'] == $cate['id_cate']) ? 'selected' : '' ?>>
                                    <?= e($cate['name_cate']) ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div>
                        <label for="min_price" class="block text-sm font-medium text-ink-700 mb-1.5">Giá từ</label>
                        <input type="number" id="min_price" name="min_price" min="0" placeholder="Giá từ"
                            value="<?= isset($_GET['min_price']) ? htmlspecialchars($_GET['min_price']) : '' ?>"
                            class="block w-full rounded-lg border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    </div>
                    <div>
                        <label for="max_price" class="block text-sm font-medium text-ink-700 mb-1.5">Đến giá</label>
                        <div class="flex gap-2">
                            <input type="number" id="max_price" name="max_price" min="0" placeholder="Đến giá"
                                value="<?= isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : '' ?>"
                                class="block w-full rounded-lg border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                            <input type="submit" name="btn-search" value="Tìm kiếm"
                                class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 whitespace-nowrap cursor-pointer">
                        </div>
                    </div>
                </div>
            </form>

            <!-- Empty state: same empty($listpro) trigger condition, restyled per design-system recipe -->
            <?php if (empty($listpro)) { ?>
                <div class="rounded-2xl border border-ink-200 bg-white py-16 text-center mb-8">
                    <i class="fa-solid fa-face-sad-tear text-5xl text-ink-300" aria-hidden="true"></i>
                    <h1 class="mt-4 font-heading text-xl font-semibold text-ink-900">Không tìm thấy sản phẩm phù hợp</h1>
                    <p class="mt-2 text-sm text-ink-500 max-w-md mx-auto">
                        Không tồn tại sản phẩm trùng khớp với từ khóa bạn nhập, vui lòng tìm kiếm sản phẩm khác.
                    </p>
                </div>
            <?php } ?>

            <!-- Product grid (product-card recipe) -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-6">
                <?php foreach ($listpro as $pro) { ?>
                    <div class="rounded-2xl border border-ink-200 bg-white shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                        <div class="relative aspect-square bg-ink-100 overflow-hidden">
                            <a href="index.php?act=prodetail&idpro=<?= e($pro['id_pro']) ?>">
                                <img src="admin/uploads/<?= e($pro['img_pro']) ?>"
                                    alt="Ảnh sản phẩm <?= e($pro['name_pro']) ?>"
                                    class="h-full w-full object-cover">
                            </a>
                            <?php if ($pro['discount'] > 0) { ?>
                                <span class="absolute top-2 left-2 rounded-full bg-red-600 text-white text-xs font-bold px-2 py-1">
                                    -<?= e($pro['discount']) ?>%
                                </span>
                            <?php } ?>
                            <?php if ((int) $pro['stock'] <= 0) { ?>
                                <span class="absolute top-2 right-2 rounded-full bg-ink-900/80 text-white text-xs font-bold px-2 py-1">
                                    <?= e($pro['stock_message'] ?: 'Hết hàng') ?>
                                </span>
                            <?php } ?>
                        </div>
                        <div class="p-4">
                            <h6 class="font-heading font-semibold text-ink-900 line-clamp-2 mb-2">
                                <a href="index.php?act=prodetail&idpro=<?= e($pro['id_pro']) ?>"
                                    class="hover:text-brand-600 transition-colors">
                                    <?= e($pro['name_pro']) ?>
                                </a>
                            </h6>
                            <div class="mb-3">
                                <?php if ($pro['discount'] <= 0) { ?>
                                    <span class="text-brand-600 font-bold">
                                        <?= number_format($pro['price']) ?>₫
                                    </span>
                                <?php } else { ?>
                                    <span class="text-brand-600 font-bold">
                                        <?= number_format(($pro['price']) - (($pro['price']) * ($pro['discount']) / 100)) ?>₫
                                    </span>
                                    <span class="text-ink-300 line-through text-sm ml-1">
                                        <?= number_format($pro['price']) ?>₫
                                    </span>
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
                                        class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 cursor-pointer">
                                </form>
                            <?php } else { ?>
                                <button type="button" disabled
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-ink-100 px-5 py-2.5 text-sm font-semibold text-ink-300 cursor-not-allowed">
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