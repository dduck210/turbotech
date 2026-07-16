<?php

/**
 * @var array $revenue_stats
 * @var array $product_sold_stats
 * @var array $inventory_stats
 */
include_once "header.php";
?>

<div class="mb-8 pb-5 border-b border-ink-300">
    <div class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600 mb-1">Báo cáo</div>
    <h1 class="font-heading text-3xl text-ink-900">Thống Kê Báo Cáo</h1>
</div>

<div class="mb-8 card-boutique rounded-lg p-6">
    <form action="./index.php?act=list_thongke" method="POST" class="flex flex-col md:flex-row gap-4 items-end">
<?= \Codemoi\Core\Csrf::field() ?>
        <div class="flex items-center gap-2 text-ink-700 font-semibold">
            <span class="w-8 h-px bg-brand-500"></span>
            Lọc theo khoảng thời gian
        </div>
        <div class="w-full md:w-auto flex flex-col sm:flex-row gap-3">
            <div>
                <label class="block text-xs font-semibold text-ink-500 mb-1.5 uppercase tracking-wider">Từ ngày</label>
                <input type="date" name="from_date" value="<?= isset($from_date) ? htmlspecialchars($from_date) : '' ?>"
                    class="w-full px-4 py-2 border border-ink-300 rounded-md focus:ring-2 focus:ring-brand-400 transition-colors bg-ink-50 text-ink-700">
            </div>
            <div>
                <label class="block text-xs font-semibold text-ink-500 mb-1.5 uppercase tracking-wider">Đến ngày</label>
                <input type="date" name="to_date" value="<?= isset($to_date) ? htmlspecialchars($to_date) : '' ?>"
                    class="w-full px-4 py-2 border border-ink-300 rounded-md focus:ring-2 focus:ring-brand-400 transition-colors bg-ink-50 text-ink-700">
            </div>
            <div>
                <label class="block text-xs font-semibold text-ink-500 mb-1.5 uppercase tracking-wider">Sắp xếp SP bán
                    ra</label>
                <select name="sort_product"
                    class="w-full px-4 py-2 border border-ink-300 rounded-md focus:ring-2 focus:ring-brand-400 transition-colors bg-ink-50 text-ink-700">
                    <option value="DESC" <?= (isset($sort_product) && $sort_product == 'DESC') ? 'selected' : '' ?>>Bán
                        nhiều nhất</option>
                    <option value="ASC" <?= (isset($sort_product) && $sort_product == 'ASC') ? 'selected' : '' ?>>Bán ít
                        nhất</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit"
                    class="btn-boutique w-full px-6 py-2 rounded-md font-medium">
                    Lọc dữ liệu
                </button>
            </div>
        </div>
    </form>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
    <!-- Revenue Stats -->
    <div class="card-boutique rounded-lg overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-ink-300">
            <h2 class="font-heading text-lg text-ink-900"><i class="fas fa-chart-line text-emerald-600 mr-2"></i>Doanh thu
                theo ngày</h2>
            <span class="block w-8 h-px bg-brand-500 mt-2"></span>
            <p class="mt-1 text-xs text-ink-500">Số tiền thực thu (đã trừ mã giảm giá)</p>
        </div>
        <div class="p-0 flex-1 overflow-auto max-h-[400px]">
            <table class="w-full text-left border-collapse text-sm">
                <thead class="sticky top-0 bg-ink-50 z-10">
                    <tr class="border-b-2 border-ink-300 text-ink-600 text-xs font-semibold tracking-wide uppercase">
                        <th class="px-6 py-3">Ngày</th>
                        <th class="px-6 py-3 text-center">Số đơn hàng</th>
                        <th class="px-6 py-3 text-right">Doanh thu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-200">
                    <?php
                    $total_revenue = 0;
                    foreach ($revenue_stats as $stat):
                        $total_revenue += $stat['total_revenue'];
                    ?>
                        <tr class="hover:bg-ink-100/60 transition-colors">
                            <td class="px-6 py-3 font-medium text-ink-700">
                                <?= date('d/m/Y', strtotime($stat['order_day'])) ?></td>
                            <td class="px-6 py-3 text-center"><span
                                    class="bg-emerald-500/15 text-emerald-700 px-2.5 py-0.5 rounded-md font-medium"><?= e($stat['total_orders']) ?></span>
                            </td>
                            <td class="px-6 py-3 text-right font-semibold text-emerald-600">
                                <?= number_format($stat['total_revenue']) ?> ₫</td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($revenue_stats)): ?>
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-ink-500">Không có dữ liệu doanh thu trong
                                khoảng thời gian này.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if (!empty($revenue_stats)): ?>
            <div class="px-6 py-3 bg-ink-100 border-t border-ink-300 flex justify-between items-center">
                <span class="font-semibold text-ink-700">Tổng doanh thu:</span>
                <span class="font-heading text-xl text-emerald-600"><?= number_format($total_revenue) ?> ₫</span>
            </div>
        <?php endif; ?>
    </div>

    <!-- Top Selling Products -->
    <div class="card-boutique rounded-lg overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-ink-300">
            <h2 class="font-heading text-lg text-ink-900"><i class="fas fa-fire text-amber-600 mr-2"></i>Sản phẩm bán chạy
            </h2>
            <span class="block w-8 h-px bg-brand-500 mt-2"></span>
            <p class="mt-1 text-xs text-ink-500">Doanh thu theo giá bán, chưa trừ mã giảm giá của đơn hàng — có thể chênh lệch với "Doanh thu theo ngày" ở đơn có áp mã</p>
        </div>
        <div class="p-0 flex-1 overflow-auto max-h-[400px]">
            <table class="w-full text-left border-collapse text-sm">
                <thead class="sticky top-0 bg-ink-50 z-10">
                    <tr class="border-b-2 border-ink-300 text-ink-600 text-xs font-semibold tracking-wide uppercase">
                        <th class="px-6 py-3">Sản phẩm</th>
                        <th class="px-6 py-3 text-center">Đã bán</th>
                        <th class="px-6 py-3 text-right">Doanh thu mang lại</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-200">
                    <?php foreach ($product_sold_stats as $stat): ?>
                        <tr class="hover:bg-ink-100/60 transition-colors">
                            <td class="px-6 py-3 flex items-center gap-3">
                                <img src="./uploads/<?= e($stat['img_pro']) ?>"
                                    class="w-10 h-10 rounded-md border border-ink-300 object-cover">
                                <span class="font-medium text-ink-800 line-clamp-1"
                                    title="<?= e($stat['name_pro']) ?>"><?= e($stat['name_pro']) ?></span>
                            </td>
                            <td class="px-6 py-3 text-center"><span
                                    class="bg-amber-500/15 text-amber-700 px-2.5 py-0.5 rounded-md font-medium"><?= e($stat['total_sold']) ?></span>
                            </td>
                            <td class="px-6 py-3 text-right font-semibold text-brand-600">
                                <?= number_format($stat['total_revenue']) ?> ₫</td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($product_sold_stats)): ?>
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-ink-500">Không có dữ liệu sản phẩm bán ra.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Inventory Stats -->
<div class="card-boutique rounded-lg overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-ink-300 flex justify-between items-center">
        <div>
            <h2 class="font-heading text-lg text-ink-900"><i class="fas fa-boxes text-blue-600 mr-2"></i>Tình trạng tồn kho (Tất
                cả sản phẩm)</h2>
            <span class="block w-8 h-px bg-brand-500 mt-2"></span>
        </div>
        <span class="text-xs font-medium text-ink-500">Sắp xếp: Tồn kho thấp nhất</span>
    </div>
    <div class="p-0 overflow-auto max-h-[500px]">
        <table class="w-full text-left border-collapse text-sm">
            <thead class="sticky top-0 bg-ink-50 z-10">
                <tr class="border-b-2 border-ink-300 text-ink-600 text-xs font-semibold tracking-wide uppercase">
                    <th class="px-6 py-3">Sản phẩm</th>
                    <th class="px-6 py-3">Đơn giá</th>
                    <th class="px-6 py-3 text-center">Tồn kho hiện tại</th>
                    <th class="px-6 py-3 text-center">Tình trạng</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink-200">
                <?php foreach ($inventory_stats as $stat):
                    $qty = $stat['stock'];
                ?>
                    <tr class="hover:bg-ink-100/60 transition-colors">
                        <td class="px-6 py-3 flex items-center gap-3">
                            <img src="./uploads/<?= e($stat['img_pro']) ?>"
                                class="w-10 h-10 rounded-md border border-ink-300 object-cover">
                            <span class="font-medium text-ink-800"><?= e($stat['name_pro']) ?></span>
                        </td>
                        <td class="px-6 py-3 font-semibold text-ink-700"><?= number_format($stat['price']) ?> ₫</td>
                        <td class="px-6 py-3 text-center font-bold <?= $qty <= 5 ? 'text-red-600' : 'text-ink-700' ?>">
                            <?= e($qty) ?></td>
                        <td class="px-6 py-3 text-center">
                            <?php if ($qty == 0): ?>
                                <span class="bg-red-500/15 text-red-700 px-2.5 py-1 rounded-md text-xs font-medium"><i
                                        class="fas fa-times-circle mr-1"></i>Hết hàng</span>
                            <?php elseif ($qty <= 5): ?>
                                <span class="bg-orange-100 text-orange-700 px-2.5 py-1 rounded-md text-xs font-medium"><i
                                        class="fas fa-exclamation-triangle mr-1"></i>Sắp hết</span>
                            <?php else: ?>
                                <span class="bg-emerald-500/15 text-emerald-700 px-2.5 py-1 rounded-md text-xs font-medium"><i
                                        class="fas fa-check-circle mr-1"></i>Còn hàng</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once "footer.php" ?>
