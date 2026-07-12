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
<div class="mb-8 flex items-center justify-between">
    <h1 class="text-3xl font-bold text-slate-800">Cập Nhật Mã Giảm Giá</h1>
    <a href="index.php?act=list_coupon"
        class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-medium rounded-lg px-5 py-2.5 transition-all flex items-center gap-2">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50 font-semibold text-slate-800">
        Thông tin mã giảm giá
    </div>
    <div class="p-6">
        <form action="index.php?act=update_coupon" method="POST">
<?= \Codemoi\Core\Csrf::field() ?>
            <input type="hidden" name="id_coupon" value="<?= isset($id_coupon) ? $id_coupon : '' ?>">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Mã giảm giá (Code) *</label>
                    <input type="text" name="code" value="<?= isset($code) ? $code : '' ?>" required
                        class="w-full rounded-lg border-slate-300 px-4 py-2 border focus:ring-2 focus:ring-brand-500 outline-none transition-all uppercase">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Loại giảm giá</label>
                    <select name="discount_type"
                        class="w-full rounded-lg border-slate-300 px-4 py-2 border focus:ring-2 focus:ring-brand-500 outline-none transition-all bg-white">
                        <option value="1" <?= (isset($discount_type) && $discount_type == 1) ? 'selected' : '' ?>>Giảm
                            theo phần trăm (%)</option>
                        <option value="2" <?= (isset($discount_type) && $discount_type == 2) ? 'selected' : '' ?>>Giảm
                            số tiền cố định (VNĐ)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Mức giảm *</label>
                    <input type="number" name="discount_value"
                        value="<?= isset($discount_value) ? $discount_value : '' ?>" required min="1"
                        class="w-full rounded-lg border-slate-300 px-4 py-2 border focus:ring-2 focus:ring-brand-500 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Mức giảm tối đa (VNĐ)</label>
                    <input type="number" name="max_discount" value="<?= isset($max_discount) ? $max_discount : '0' ?>"
                        min="0"
                        class="w-full rounded-lg border-slate-300 px-4 py-2 border focus:ring-2 focus:ring-brand-500 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Giá trị đơn hàng tối thiểu
                        (VNĐ)</label>
                    <input type="number" name="min_order_value"
                        value="<?= isset($min_order_value) ? $min_order_value : '0' ?>" min="0"
                        class="w-full rounded-lg border-slate-300 px-4 py-2 border focus:ring-2 focus:ring-brand-500 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Áp dụng cho mặt hàng</label>
                    <select name="product_id"
                        class="w-full rounded-lg border-slate-300 px-4 py-2 border focus:ring-2 focus:ring-brand-500 outline-none transition-all bg-white">
                        <option value="0" <?= (!isset($product_id) || $product_id == 0) ? 'selected' : '' ?>>Tất cả sản
                            phẩm</option>
                        <?php foreach ($listpro as $pro): ?>
                            <option value="<?= $pro['id_pro'] ?>"
                                <?= (isset($product_id) && $product_id == $pro['id_pro']) ? 'selected' : '' ?>>
                                <?= $pro['name_pro'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Thời gian bắt đầu *</label>
                    <input type="datetime-local" name="start_date"
                        value="<?= isset($start_date_formatted) ? $start_date_formatted : '' ?>" required
                        class="w-full rounded-lg border-slate-300 px-4 py-2 border focus:ring-2 focus:ring-brand-500 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Thời gian kết thúc *</label>
                    <input type="datetime-local" name="end_date"
                        value="<?= isset($end_date_formatted) ? $end_date_formatted : '' ?>" required
                        class="w-full rounded-lg border-slate-300 px-4 py-2 border focus:ring-2 focus:ring-brand-500 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Giới hạn số lượt dùng</label>
                    <input type="number" name="usage_limit" value="<?= isset($usage_limit) ? $usage_limit : '0' ?>"
                        min="0"
                        class="w-full rounded-lg border-slate-300 px-4 py-2 border focus:ring-2 focus:ring-brand-500 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Trạng thái</label>
                    <select name="status"
                        class="w-full rounded-lg border-slate-300 px-4 py-2 border focus:ring-2 focus:ring-brand-500 outline-none transition-all bg-white">
                        <option value="1" <?= (isset($status) && $status == 1) ? 'selected' : '' ?>>Đang hoạt động
                        </option>
                        <option value="0" <?= (isset($status) && $status == 0) ? 'selected' : '' ?>>Tạm tắt</option>
                    </select>
                </div>
            </div>

            <div class="mt-8 border-t border-slate-100 pt-6">
                <button type="submit" name="btn_update"
                    class="bg-brand-600 hover:bg-brand-700 text-white font-medium rounded-lg px-6 py-2.5 transition-colors inline-block">
                    Cập Nhật
                </button>
            </div>
        </form>
    </div>
</div>
<?php include_once "footer.php" ?>