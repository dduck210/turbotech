<?php include_once "header.php" ?>
<?php if (!empty($flash_success)): ?>
<script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"success",title:<?= json_encode($flash_success) ?>,showConfirmButton:false,timer:3000}));</script>
<?php endif; ?>
<div class="mb-8 flex items-center justify-between">
    <h1 class="text-3xl font-bold text-ink-800">Quản Lý Mã Giảm Giá</h1>
    <a href="index.php?act=add_coupon"
        class="bg-brand-600 hover:bg-brand-700 text-white font-medium rounded-lg px-5 py-2.5 transition-all shadow-sm flex items-center gap-2">
        <i class="fas fa-plus"></i> Thêm mã mới
    </a>
</div>

<div class="bg-ink-200/70 backdrop-blur-xl rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden mb-6">
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-ink-50 text-ink-700 uppercase text-xs font-semibold border-b border-ink-200">
                        <th class="px-4 py-3">ID</th>
                        <th class="px-4 py-3">Mã giảm giá</th>
                        <th class="px-4 py-3">Mức giảm</th>
                        <th class="px-4 py-3">Điều kiện</th>
                        <th class="px-4 py-3">Thời gian</th>
                        <th class="px-4 py-3 text-center">Lượt dùng</th>
                        <th class="px-4 py-3 text-center">Trạng thái</th>
                        <th class="px-4 py-3 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $listcoupon = $listcoupon ?? [];
                    foreach ($listcoupon as $coupon): ?>
                    <tr class="border-b border-ink-100 hover:bg-ink-50 transition-colors">
                        <td class="px-4 py-4 font-medium text-ink-900">#<?= e($coupon['id_coupon']) ?></td>
                        <td class="px-4 py-4"><span
                                class="bg-brand-500/15 text-brand-700 px-3 py-1 rounded-md font-mono font-bold"><?= e($coupon['code']) ?></span>
                        </td>
                        <td class="px-4 py-4">
                            <?php if ($coupon['discount_type'] == 1): ?>
                            <span class="text-emerald-600 font-bold"><?= e($coupon['discount_value']) ?>%</span>
                            <?php if ($coupon['max_discount'] > 0): ?>
                            <div class="text-xs text-ink-500 mt-1">Tối đa:
                                <?= number_format($coupon['max_discount']) ?>đ</div>
                            <?php endif; ?>
                            <?php else: ?>
                            <span
                                class="text-emerald-600 font-bold"><?= number_format($coupon['discount_value']) ?>đ</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-4 text-sm text-ink-600">
                            <div>Đơn từ: <span
                                    class="font-semibold"><?= number_format($coupon['min_order_value']) ?>đ</span></div>
                            <?php if ($coupon['product_id'] > 0): ?>
                            <div class="text-brand-600 mt-1"><i class="fas fa-box text-xs mr-1"></i>Chỉ SP ID:
                                <?= e($coupon['product_id']) ?></div>
                            <?php else: ?>
                            <div class="text-ink-500 mt-1"><i class="fas fa-layer-group text-xs mr-1"></i>Tất cả SP
                            </div>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-4 text-xs text-ink-600">
                            <div><span class="font-semibold text-ink-500">Từ:</span>
                                <?= date('d/m/Y H:i', strtotime($coupon['start_date'])) ?></div>
                            <div class="mt-1"><span class="font-semibold text-ink-500">Đến:</span>
                                <?= date('d/m/Y H:i', strtotime($coupon['end_date'])) ?></div>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="font-bold text-ink-700"><?= e($coupon['used_count']) ?></span>
                            <span class="text-ink-400">/
                                <?= $coupon['usage_limit'] > 0 ? e($coupon['usage_limit']) : '&infin;' ?></span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <?php if ($coupon['status'] == 1): ?>
                            <span
                                class="bg-emerald-500/15 text-emerald-700 px-3 py-1 rounded-full text-xs font-medium">Hoạt
                                động</span>
                            <?php else: ?>
                            <span class="bg-red-500/15 text-red-700 px-3 py-1 rounded-full text-xs font-medium">Đã
                                tắt</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="index.php?act=edit_coupon&id_coupon=<?= e($coupon['id_coupon']) ?>"
                                    class="p-2 text-amber-600 bg-amber-500/10 rounded-lg hover:bg-amber-500/15 transition-all active:scale-90"
                                    title="Sửa"><i class="fas fa-edit"></i></a>
                                <a href="index.php?act=delete_coupon&id_coupon=<?= e($coupon['id_coupon']) ?>&_token=<?= urlencode(\Codemoi\Core\Csrf::token()) ?>"
                                    class="p-2 text-red-600 bg-red-500/10 rounded-lg hover:bg-red-500/15 transition-all active:scale-90"
                                    data-confirm="Bạn có chắc chắn muốn xóa mã giảm giá này?" title="Xóa"><i
                                        class="fas fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($listcoupon)): ?>
                    <tr>
                        <td colspan="8" class="px-4 py-16 text-center">
                            <i class="fas fa-tag text-5xl text-ink-600" aria-hidden="true"></i>
                            <p class="mt-4 font-semibold text-ink-700">Chưa có mã giảm giá nào</p>
                            <p class="mt-1 text-sm text-ink-500">Bấm "Thêm mã mới" ở trên để tạo mã giảm giá đầu tiên.</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include_once "footer.php" ?>