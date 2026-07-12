<?php

/**
 * Partial: renders a persisted order's cart lines as an HTML table.
 * Inlined from the old `cart_detail($listcart)` helper (`model/giohang.php`),
 * used by `billconfirm.php` via `include` (so it shares the including
 * file's local scope — `$cart_detail` is already defined there, extracted
 * by `Codemoi\Core\View::render()`).
 *
 * @var array $cart_detail Persisted cart lines (Codemoi\Model\Order::items()).
 */
$total_amount = 0;
?>
<div class="rounded-2xl border border-ink-200 bg-white shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-ink-50 text-xs uppercase tracking-wide text-ink-500">
                <tr>
                    <th class="px-4 py-3" scope="col">Hình ảnh</th>
                    <th class="px-4 py-3" scope="col">Sản phẩm</th>
                    <th class="px-4 py-3" scope="col">Đơn giá</th>
                    <th class="px-4 py-3 text-center" scope="col">Số lượng</th>
                    <th class="px-4 py-3" scope="col">Thành tiền</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink-100">
                <?php foreach ($cart_detail as $cart) {
                    $img_pro = "admin/uploads/" . e($cart['img_pro']);
                    $prodetail = "index.php?act=prodetail&idpro=" . e($cart['id_pro']);
                    $total_amount += $cart['total_amount'];
                ?>
                    <tr>
                        <td class="px-4 py-3">
                            <img src="<?= $img_pro ?>" alt="<?= e($cart['name_pro']) ?>" class="h-16 w-16 rounded-lg bg-ink-100 object-cover" />
                        </td>
                        <td class="px-4 py-3">
                            <a href="<?= $prodetail ?>" class="font-heading font-semibold text-ink-900 hover:text-brand-600 transition-colors"><?= e($cart['name_pro']) ?></a>
                        </td>
                        <td class="px-4 py-3 text-ink-700"><?= number_format($cart['price_pro']) ?> ₫</td>
                        <td class="px-4 py-3 text-center text-ink-700"><?= e($cart['quantity']) ?></td>
                        <td class="px-4 py-3 font-semibold text-ink-900"><?= number_format($cart['total_amount']) ?> ₫</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<div class="mt-6 flex justify-end">
    <div class="w-full md:w-96 rounded-2xl border border-ink-200 bg-white p-6 shadow-sm">
        <h2 class="font-heading text-lg font-semibold text-ink-900 mb-4">Tổng giỏ hàng</h2>
        <div class="flex items-center justify-between text-sm text-ink-700">
            <span>Tổng thành tiền</span>
            <span class="text-lg font-bold text-brand-600"><?= number_format($total_amount) ?> ₫</span>
        </div>
    </div>
</div>