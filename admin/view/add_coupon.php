<?php

/** @var array $listpro */
include_once "header.php";
?>
<div class="mb-8 flex items-center justify-between">
    <h1 class="text-3xl font-bold text-ink-800">Thêm Mã Giảm Giá Mới</h1>
    <a href="index.php?act=list_coupon"
        class="bg-ink-200 hover:bg-ink-300 text-ink-700 font-medium rounded-lg px-5 py-2.5 transition-all flex items-center gap-2">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-ink-200 overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-ink-200 bg-ink-50/50 font-semibold text-ink-800">
        Thông tin mã giảm giá
    </div>
    <div class="p-6">
        <form action="index.php?act=add_coupon" method="POST">
<?= \Codemoi\Core\Csrf::field() ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-ink-700 mb-2">Mã giảm giá (Code) *</label>
                    <input type="text" name="code" required
                        class="w-full rounded-lg border-ink-300 px-4 py-2 border focus:ring-2 focus:ring-brand-500 outline-none transition-all uppercase"
                        placeholder="VD: SUMMER2024">
                </div>

                <div>
                    <label class="block text-sm font-medium text-ink-700 mb-2">Loại giảm giá</label>
                    <select name="discount_type"
                        class="w-full rounded-lg border-ink-300 px-4 py-2 border focus:ring-2 focus:ring-brand-500 outline-none transition-all bg-white">
                        <option value="1">Giảm theo phần trăm (%)</option>
                        <option value="2">Giảm số tiền cố định (VNĐ)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-ink-700 mb-2">Mức giảm *</label>
                    <input type="number" name="discount_value" required min="1"
                        class="w-full rounded-lg border-ink-300 px-4 py-2 border focus:ring-2 focus:ring-brand-500 outline-none transition-all"
                        placeholder="Nhập số % hoặc số tiền">
                </div>

                <div>
                    <label class="block text-sm font-medium text-ink-700 mb-2">Mức giảm tối đa (VNĐ)</label>
                    <input type="number" name="max_discount" value="0" min="0"
                        class="w-full rounded-lg border-ink-300 px-4 py-2 border focus:ring-2 focus:ring-brand-500 outline-none transition-all"
                        placeholder="Để 0 nếu không giới hạn">
                    <p class="text-xs text-ink-500 mt-1">Chỉ áp dụng khi loại giảm giá là Phần trăm.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-ink-700 mb-2">Giá trị đơn hàng tối thiểu
                        (VNĐ)</label>
                    <input type="number" name="min_order_value" value="0" min="0"
                        class="w-full rounded-lg border-ink-300 px-4 py-2 border focus:ring-2 focus:ring-brand-500 outline-none transition-all"
                        placeholder="Để 0 nếu áp dụng mọi đơn">
                </div>

                <div>
                    <label class="block text-sm font-medium text-ink-700 mb-2">Áp dụng cho mặt hàng</label>
                    <select name="product_id"
                        class="w-full rounded-lg border-ink-300 px-4 py-2 border focus:ring-2 focus:ring-brand-500 outline-none transition-all bg-white">
                        <option value="0">Tất cả sản phẩm</option>
                        <?php foreach ($listpro as $pro): ?>
                            <option value="<?= e($pro['id_pro']) ?>"><?= e($pro['name_pro']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-ink-700 mb-2">Thời gian bắt đầu *</label>
                    <input type="datetime-local" name="start_date" required
                        class="w-full rounded-lg border-ink-300 px-4 py-2 border focus:ring-2 focus:ring-brand-500 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-medium text-ink-700 mb-2">Thời gian kết thúc *</label>
                    <input type="datetime-local" name="end_date" required
                        class="w-full rounded-lg border-ink-300 px-4 py-2 border focus:ring-2 focus:ring-brand-500 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-medium text-ink-700 mb-2">Giới hạn số lượt dùng</label>
                    <input type="number" name="usage_limit" value="0" min="0"
                        class="w-full rounded-lg border-ink-300 px-4 py-2 border focus:ring-2 focus:ring-brand-500 outline-none transition-all"
                        placeholder="Để 0 nếu không giới hạn">
                </div>

                <div>
                    <label class="block text-sm font-medium text-ink-700 mb-2">Trạng thái</label>
                    <select name="status"
                        class="w-full rounded-lg border-ink-300 px-4 py-2 border focus:ring-2 focus:ring-brand-500 outline-none transition-all bg-white">
                        <option value="1">Đang hoạt động</option>
                        <option value="0">Tạm tắt</option>
                    </select>
                </div>
            </div>

            <div class="mt-8 border-t border-ink-100 pt-6">
                <button type="submit" name="btn_add"
                    class="bg-brand-600 hover:bg-brand-700 text-white font-medium rounded-lg px-6 py-2.5 transition-colors inline-block">
                    Thêm Mã Mới
                </button>
                <button type="reset"
                    class="bg-ink-100 hover:bg-ink-200 text-ink-700 font-medium rounded-lg px-6 py-2.5 transition-colors inline-block ml-2">
                    Nhập lại
                </button>
            </div>
        </form>
    </div>
</div>
<?php include_once "footer.php" ?>