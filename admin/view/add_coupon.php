<?php

/** @var array $listpro */
include_once "header.php";
?>
<div class="mb-8 pb-5 border-b border-ink-300 flex flex-wrap items-end justify-between gap-4">
    <div>
        <div class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600 mb-1">Ưu đãi</div>
        <h1 class="font-heading text-3xl text-ink-900">Thêm Mã Giảm Giá Mới</h1>
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
        <form action="index.php?act=add_coupon" method="POST">
<?= \Codemoi\Core\Csrf::field() ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Mã giảm giá (Code) *</label>
                    <input type="text" name="code" required
                        class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800 uppercase"
                        placeholder="VD: SUMMER2024">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Loại giảm giá</label>
                    <select name="discount_type"
                        class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800">
                        <option value="1">Giảm theo phần trăm (%)</option>
                        <option value="2">Giảm số tiền cố định (VNĐ)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Mức giảm *</label>
                    <input type="number" name="discount_value" required min="1"
                        class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800"
                        placeholder="Nhập số % hoặc số tiền">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Mức giảm tối đa (VNĐ)</label>
                    <input type="number" name="max_discount" value="0" min="0"
                        class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800"
                        placeholder="Để 0 nếu không giới hạn">
                    <p class="text-xs text-ink-500 mt-1">Chỉ áp dụng khi loại giảm giá là Phần trăm.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Giá trị đơn hàng tối thiểu
                        (VNĐ)</label>
                    <input type="number" name="min_order_value" value="0" min="0"
                        class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800"
                        placeholder="Để 0 nếu áp dụng mọi đơn">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Áp dụng cho mặt hàng</label>
                    <select name="product_id"
                        class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800">
                        <option value="0">Tất cả sản phẩm</option>
                        <?php foreach ($listpro as $pro): ?>
                            <option value="<?= e($pro['id_pro']) ?>"><?= e($pro['name_pro']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Thời gian bắt đầu *</label>
                    <input type="datetime-local" name="start_date" required
                        class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Thời gian kết thúc *</label>
                    <input type="datetime-local" name="end_date" required
                        class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Giới hạn số lượt dùng</label>
                    <input type="number" name="usage_limit" value="0" min="0"
                        class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800"
                        placeholder="Để 0 nếu không giới hạn">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Trạng thái</label>
                    <select name="status"
                        class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800">
                        <option value="1">Đang hoạt động</option>
                        <option value="0">Tạm tắt</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-8 border-t border-ink-200 pt-6">
                <button type="submit" name="btn_add"
                    class="btn-boutique rounded-md px-6 py-2.5 font-medium">
                    Thêm Mã Mới
                </button>
                <button type="reset"
                    class="border border-ink-300 text-ink-700 hover:bg-ink-200 rounded-md px-6 py-2.5 transition-colors">
                    Nhập lại
                </button>
            </div>
        </form>
    </div>
</div>
<?php include_once "footer.php" ?>
