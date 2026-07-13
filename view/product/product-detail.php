<?php

/**
 * @var array $one_pro     Product row (Codemoi\Model\Product::one()).
 * @var array $similar_pro Other products in the same category (Codemoi\Model\Product::similar()).
 */
?>
<!-- Breadcrumb -->
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-4">
    <nav aria-label="Breadcrumb" class="text-sm text-ink-500">
        <ol class="flex items-center gap-2">
            <li><a href="index.php" class="hover:text-brand-600 transition-colors">Trang chủ</a></li>
            <li aria-hidden="true">/</li>
            <li class="text-ink-900 font-medium">Chi tiết sản phẩm</li>
        </ol>
    </nav>
</div>
<!-- End Breadcrumb -->

<!-- Product detail: image + info
     Original image gallery used slick ("sp-largeimages"/"sp-thumbs") + lightGallery ("sp-imagezoom")
     on a SINGLE repeated image (no real multi-image data) — per design-system's legacy-plugin
     removal guidance, dropped the slider/lightbox plugin classes entirely and show one static image. -->
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12 md:py-16">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">

        <!-- Image -->
        <div class="rounded-2xl border border-ink-200 bg-ink-800/70 backdrop-blur-xl overflow-hidden">
            <div class="aspect-square bg-ink-100 overflow-hidden">
                <img src="admin/uploads/<?= e($one_pro['img_pro']) ?>"
                    alt="Ảnh sản phẩm <?= e($one_pro['name_pro']) ?>"
                    class="h-full w-full object-cover">
            </div>
        </div>

        <!-- Info -->
        <div>
            <h1 class="font-heading text-2xl md:text-3xl font-bold text-ink-900 mb-2">
                <a href="index.php?act=prodetail&idpro=<?= e($one_pro['id_pro']) ?>"
                    class="hover:text-brand-600 transition-colors"><?= e($one_pro['name_pro']) ?></a>
            </h1>
            <p class="text-sm text-ink-500 mb-4">Lượt xem: <?= e($one_pro['view']) ?></p>

            <div class="mb-4">
                <?php if ($one_pro['discount'] <= 0) { ?>
                    <span class="text-2xl font-bold text-brand-600"><?= number_format($one_pro['price']) ?>₫</span>
                <?php } else { ?>
                    <span class="text-2xl font-bold text-brand-600">
                        <?= number_format(($one_pro['price']) - (($one_pro['price']) * ($one_pro['discount']) / 100)) ?>₫
                    </span>
                    <span class="ml-2 text-ink-300 line-through"><?= number_format($one_pro['price']) ?>₫</span>
                <?php } ?>
            </div>

            <div class="mb-4">
                <?php if ((int) $one_pro['stock'] > 0) { ?>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1 text-xs font-medium text-green-700">
                        <i class="fa-solid fa-circle-check" aria-hidden="true"></i> Còn hàng (<?= (int) $one_pro['stock'] ?> sản phẩm)
                    </span>
                <?php } else { ?>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-3 py-1 text-xs font-medium text-red-700">
                        <i class="fa-solid fa-circle-xmark" aria-hidden="true"></i> <?= htmlspecialchars($one_pro['stock_message'] ?: 'Hết hàng') ?>
                    </span>
                <?php } ?>
            </div>

            <div class="text-ink-700 leading-relaxed mb-6">
                <p><?= e($one_pro['short_des']) ?></p>
            </div>

            <!-- Add to cart form: field names / values byte-identical to before.
                 Quantity stepper markup (cart-plus-minus / cart-plus-minus-box / dec /
                 inc / qtybutton classes, name="quatity") kept exactly — these are hooked by
                 the vanilla-JS handler in assets/js/main.js, only restyled visually.
                 Hidden entirely (not just disabled) when out of stock, matching backend
                 guard in CartController::add(). -->
            <?php if ((int) $one_pro['stock'] > 0) { ?>
                <form action="index.php?act=addtocart" method="post" class="mb-6">
<?= \Codemoi\Core\Csrf::field() ?>
                    <div class="flex flex-wrap items-end gap-4 mb-5">
                        <div>
                            <label for="quatity" class="block text-sm font-medium text-ink-700 mb-1.5">Số lượng</label>
                            <div class="cart-plus-minus inline-flex items-stretch rounded-lg border border-ink-200 overflow-hidden">
                                <input class="cart-plus-minus-box w-16 h-11 text-center text-sm text-ink-900 focus:outline-none"
                                    id="quatity" name="quatity" value="1" type="text">
                                <div class="dec qtybutton flex items-center justify-center w-11 h-11 border-l border-ink-200 hover:bg-ink-50 cursor-pointer">
                                    <i class="fa fa-angle-down" aria-hidden="true"></i>
                                </div>
                                <div class="inc qtybutton flex items-center justify-center w-11 h-11 border-l border-ink-200 hover:bg-ink-50 cursor-pointer">
                                    <i class="fa fa-angle-up" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="id_pro" value="<?= e($one_pro['id_pro']) ?>">
                        <input type="hidden" name="name_pro" value="<?= e($one_pro['name_pro']) ?>">
                        <input type="hidden" name="img_pro" value="<?= e($one_pro['img_pro']) ?>">
                        <input type="hidden" name="price" value="<?= e($one_pro['price']) ?>">
                        <input type="submit" name="addtocart" value="Thêm giỏ hàng"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 cursor-pointer">
                    </div>

                    <ul class="space-y-2 text-sm text-ink-700">
                        <li class="flex items-center gap-2"><i class="fas fa-check-square text-brand-600" aria-hidden="true"></i>Bảo hành chính hãng</li>
                        <li class="flex items-center gap-2"><i class="fa fa-truck text-brand-600" aria-hidden="true"></i>Giao hàng nhanh chóng</li>
                        <li class="flex items-center gap-2"><i class="fas fa-sync-alt text-brand-600" aria-hidden="true"></i>Chế độ đổi trả trong vòng 12 tháng</li>
                    </ul>
                </form>
            <?php } else { ?>
                <button type="button" disabled
                    class="mb-6 inline-flex items-center justify-center gap-2 rounded-lg bg-ink-100 px-5 py-2.5 text-sm font-semibold text-ink-300 cursor-not-allowed">
                    <?= htmlspecialchars($one_pro['stock_message'] ?: 'Hết hàng') ?>
                </button>
            <?php } ?>
        </div>
    </div>
</div>
<!-- End Product detail -->

<!-- Description / Reviews tabs
     Plain vanilla-JS click handler (script at the bottom of this file) — Bootstrap's
     data-bs-toggle="tab" data-api does NOT actually fire in this theme's bundled
     plugins.min.js (verified: bootstrap object loads but the Tab component's click
     delegation never toggles .active), so don't rely on it. Same proven pattern as
     the account-page tabs in view/user/myaccount.php.
     Comment widget (public/view/comment-form.php, owned by another agent) AJAX-load left untouched. -->
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12 md:py-16">
    <div class="border-b border-ink-200 mb-6">
        <ul class="nav flex gap-2" role="tablist">
            <li role="presentation">
                <a class="detail-tab-trigger active inline-flex items-center gap-1.5 border-b-2 border-transparent px-4 py-2.5 text-sm font-semibold text-ink-500 transition-colors hover:text-brand-600 [&.active]:border-brand-600 [&.active]:text-brand-600"
                    data-tab-target="description" href="#description" role="tab" aria-controls="description">
                    <span>Chi tiết</span>
                </a>
            </li>
            <li role="presentation">
                <a class="detail-tab-trigger inline-flex items-center gap-1.5 border-b-2 border-transparent px-4 py-2.5 text-sm font-semibold text-ink-500 transition-colors hover:text-brand-600 [&.active]:border-brand-600 [&.active]:text-brand-600"
                    data-tab-target="reviews" href="#reviews" role="tab" aria-controls="reviews">
                    <span>Đánh giá</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="tab-content">
        <div id="description" data-tab-panel class="tab-pane active hidden [&.active]:block!" role="tabpanel">
            <div class="text-ink-700 leading-relaxed">
                <p>
                    <strong class="text-lg text-ink-900">Thông số kỹ thuật:</strong><br>
                    <?php /* Not escaped: authored by admins via the CKEditor rich-text field
                             (admin/view/{add,update}_product.php), intentionally stores HTML. */ ?>
                    <?= $one_pro['detail_des'] ?>
                </p>
            </div>
        </div>

        <div id="reviews" data-tab-panel class="tab-pane hidden [&.active]:block!" role="tabpanel">
            <!-- jquery bình luận -->
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
            <script>
                $(document).ready(function() {
                    $("#comment").load("./view/comment-form.php", {
                        idpro: <?= json_encode($one_pro['id_pro']) ?>,
                    });
                });
            </script>
            <div id="comment"></div>
        </div>
    </div>
</div>
<!-- End Description / Reviews tabs -->

<!-- Similar products
     NOTE: the original file had TWO copies of this block — the first wrapped in a `hidden`
     attribute and explicitly commented as dead ("hidden vì ko fix được nữa đành phải coi là thẻ
     ảo" = kept only as a phantom tag, never rendered). That dead duplicate is removed here
     (documented in report) — only the real, visible block remains. Carousel classes
     ("jb-product_slider", slick-initialized) dropped per design-system guidance, replaced with a
     responsive grid. Decorative non-functional wishlist/copy "#" links (no JS behavior, verified
     against assets/js/main.js) dropped as dead UI per YAGNI. -->
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12 md:py-16">
    <h4 class="font-heading text-xl font-semibold text-ink-900 mb-6">Các sản phẩm cùng loại</h4>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
        <?php foreach ($similar_pro as $pro) { ?>
            <div class="rounded-2xl border border-ink-200 bg-ink-800/70 backdrop-blur-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                <div class="relative aspect-square bg-ink-100 overflow-hidden">
                    <a href="index.php?act=prodetail&idpro=<?= e($pro['id_pro']) ?>">
                        <img src="admin/uploads/<?= e($pro['img_pro']) ?>"
                            alt="Ảnh sản phẩm <?= e($pro['name_pro']) ?>"
                            class="h-full w-full object-cover">
                    </a>
                    <?php if ((int) $pro['stock'] <= 0) { ?>
                        <span class="absolute top-2 right-2 rounded-full bg-ink-900/80 text-white text-xs font-bold px-2 py-1"><?= e($pro['stock_message'] ?: 'Hết hàng') ?></span>
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
                        <span class="text-brand-600 font-bold">
                            <?php echo number_format($pro['price']); ?>₫
                        </span>
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
<!-- End Similar products -->

<script>
    (function() {
        var triggers = document.querySelectorAll('.detail-tab-trigger');
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