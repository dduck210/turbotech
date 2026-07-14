<?php include_once "header.php" ?>
<?php
$one_coupon = $one_coupon ?? [];
if (is_array($one_coupon)) {
    extract($one_coupon);
    // Format datetime-local requires YYYY-MM-DDThh:mm
    $start_date_formatted = date('Y-m-d\TH:i', strtotime($start_date));
    $end_date_formatted = date('Y-m-d\TH:i', strtotime($end_date));
}
?>
<div class="mb-8 pb-5 border-b border-ink-300 flex flex-wrap items-end justify-between gap-4">
    <div>
        <div class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600 mb-1">Ưu đãi</div>
        <h1 class="font-heading text-3xl text-ink-900">Cập Nhật Mã Giảm Giá</h1>
    </div>
    <a href="index.php?act=list_coupon"
        class="inline-flex items-center gap-2 border border-ink-300 text-ink-700 font-medium rounded-md px-5 py-2.5 hover:bg-ink-200 transition-colors">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<div class="card-boutique rounded-lg overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-ink-300">
        <h2 class="font-heading text-lg text-ink-900">Thông tin mã giảm giá</h2>
        <span class="block w-8 h-px bg-brand-500 mt-2"></span>
    </div>
    <div class="p-6">
        <form action="index.php?act=update_coupon" method="POST">
<?= \Codemoi\Core\Csrf::field() ?>
            <input type="hidden" name="id_coupon" value="<?= e($id_coupon ?? '') ?>">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Mã giảm giá (Code) *</label>
                    <input type="text" name="code" value="<?= e($code ?? '') ?>" required
                        class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800 uppercase">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Loại giảm giá</label>
                    <select name="discount_type"
                        class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800">
                        <option value="1" <?= (isset($discount_type) && $discount_type == 1) ? 'selected' : '' ?>>Giảm
                            theo phần trăm (%)</option>
                        <option value="2" <?= (isset($discount_type) && $discount_type == 2) ? 'selected' : '' ?>>Giảm
                            số tiền cố định (VNĐ)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Mức giảm *</label>
                    <input type="number" name="discount_value"
                        value="<?= e($discount_value ?? '') ?>" required min="1"
                        class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Mức giảm tối đa (VNĐ)</label>
                    <input type="number" name="max_discount" value="<?= e($max_discount ?? '0') ?>"
                        min="0"
                        class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Giá trị đơn hàng tối thiểu
                        (VNĐ)</label>
                    <input type="number" name="min_order_value"
                        value="<?= e($min_order_value ?? '0') ?>" min="0"
                        class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Áp dụng cho mặt hàng</label>
                    <select name="product_id"
                        class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800">
                        <option value="0" <?= (!isset($product_id) || $product_id == 0) ? 'selected' : '' ?>>Tất cả sản
                            phẩm</option>
                        <?php foreach ($listpro as $pro): ?>
                            <option value="<?= e($pro['id_pro']) ?>"
                                <?= (isset($product_id) && $product_id == $pro['id_pro']) ? 'selected' : '' ?>>
                                <?= e($pro['name_pro']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Thời gian bắt đầu *</label>
                    <input type="datetime-local" name="start_date"
                        value="<?= e($start_date_formatted ?? '') ?>" required
                        class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Thời gian kết thúc *</label>
                    <input type="datetime-local" name="end_date"
                        value="<?= e($end_date_formatted ?? '') ?>" required
                        class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Giới hạn số lượt dùng</label>
                    <input type="number" name="usage_limit" value="<?= e($usage_limit ?? '0') ?>"
                        min="0"
                        class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Trạng thái</label>
                    <select name="status"
                        class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800">
                        <option value="1" <?= (isset($status) && $status == 1) ? 'selected' : '' ?>>Đang hoạt động
                        </option>
                        <option value="0" <?= (isset($status) && $status == 0) ? 'selected' : '' ?>>Tạm tắt</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-8 border-t border-ink-200 pt-6">
                <button type="submit" name="btn_update"
                    class="btn-boutique rounded-md px-6 py-2.5 font-medium">
                    Cập Nhật
                </button>
            </div>
        </form>
    </div>
</div>
<?php include_once "footer.php" ?>
