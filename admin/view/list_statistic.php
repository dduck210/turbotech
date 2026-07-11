<?php 
/** 
 * @var array $revenue_stats 
 * @var array $product_sold_stats 
 * @var array $inventory_stats 
 */
include_once "header.php";
?>

<div class="mb-8 flex items-center justify-between">
    <h1 class="text-3xl font-bold text-slate-800">Thống Kê Báo Cáo</h1>
</div>

<div class="mb-6 bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
    <form action="./index.php?act=list_thongke" method="POST" class="flex flex-col md:flex-row gap-4 items-end">
        <div class="flex-1 text-slate-700 font-semibold">
            <i class="fas fa-filter mr-2 text-brand-500"></i>Lọc thống kê theo khoảng thời gian
        </div>
        <div class="w-full md:w-auto flex flex-col sm:flex-row gap-3">
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1 uppercase tracking-wider">Từ ngày</label>
                <input type="date" name="from_date" value="<?= isset($from_date) ? htmlspecialchars($from_date) : '' ?>" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 transition-colors bg-slate-50 text-slate-700">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1 uppercase tracking-wider">Đến ngày</label>
                <input type="date" name="to_date" value="<?= isset($to_date) ? htmlspecialchars($to_date) : '' ?>" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 transition-colors bg-slate-50 text-slate-700">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1 uppercase tracking-wider">Sắp xếp SP bán ra</label>
                <select name="sort_product" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 transition-colors bg-slate-50 text-slate-700">
                    <option value="DESC" <?= (isset($sort_product) && $sort_product == 'DESC') ? 'selected' : '' ?>>Bán nhiều nhất</option>
                    <option value="ASC" <?= (isset($sort_product) && $sort_product == 'ASC') ? 'selected' : '' ?>>Bán ít nhất</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-6 py-2.5 bg-brand-600 text-white font-medium rounded-lg hover:bg-brand-700 focus:ring-4 focus:ring-brand-300 transition-all cursor-pointer shadow-sm">
                    Lọc dữ liệu
                </button>
            </div>
        </div>
    </form>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
    <!-- Revenue Stats -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50 flex justify-between items-center">
            <h2 class="font-semibold text-slate-800"><i class="fas fa-chart-line text-emerald-500 mr-2"></i>Doanh thu theo ngày</h2>
        </div>
        <div class="p-0 flex-1 overflow-auto max-h-[400px]">
            <table class="w-full text-left border-collapse text-sm">
                <thead class="sticky top-0 bg-slate-50 z-10 shadow-sm">
                    <tr class="text-slate-600 text-xs font-semibold uppercase tracking-wide">
                        <th class="px-6 py-3 border-b border-slate-200">Ngày</th>
                        <th class="px-6 py-3 border-b border-slate-200 text-center">Số đơn hàng</th>
                        <th class="px-6 py-3 border-b border-slate-200 text-right">Doanh thu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php 
                    $total_revenue = 0;
                    foreach ($revenue_stats as $stat): 
                        $total_revenue += $stat['total_revenue'];
                    ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-3 font-medium text-slate-700"><?= date('d/m/Y', strtotime($stat['order_day'])) ?></td>
                            <td class="px-6 py-3 text-center"><span class="bg-emerald-100 text-emerald-700 px-2.5 py-0.5 rounded-full font-medium"><?= $stat['total_orders'] ?></span></td>
                            <td class="px-6 py-3 text-right font-semibold text-emerald-600"><?= number_format($stat['total_revenue']) ?> ₫</td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if(empty($revenue_stats)): ?>
                        <tr><td colspan="3" class="px-6 py-8 text-center text-slate-500">Không có dữ liệu doanh thu trong khoảng thời gian này.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if(!empty($revenue_stats)): ?>
        <div class="px-6 py-3 bg-slate-50 border-t border-slate-200 flex justify-between items-center">
            <span class="font-semibold text-slate-700">Tổng doanh thu:</span>
            <span class="font-bold text-xl text-emerald-600"><?= number_format($total_revenue) ?> ₫</span>
        </div>
        <?php endif; ?>
    </div>

    <!-- Top Selling Products -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50 flex justify-between items-center">
            <h2 class="font-semibold text-slate-800"><i class="fas fa-fire text-amber-500 mr-2"></i>Sản phẩm bán chạy</h2>
        </div>
        <div class="p-0 flex-1 overflow-auto max-h-[400px]">
            <table class="w-full text-left border-collapse text-sm">
                <thead class="sticky top-0 bg-slate-50 z-10 shadow-sm">
                    <tr class="text-slate-600 text-xs font-semibold uppercase tracking-wide">
                        <th class="px-6 py-3 border-b border-slate-200">Sản phẩm</th>
                        <th class="px-6 py-3 border-b border-slate-200 text-center">Đã bán</th>
                        <th class="px-6 py-3 border-b border-slate-200 text-right">Doanh thu mang lại</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($product_sold_stats as $stat): ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-3 flex items-center gap-3">
                                <img src="./uploads/<?= $stat['img_pro'] ?>" class="w-10 h-10 rounded-md border border-slate-200 object-cover">
                                <span class="font-medium text-slate-800 line-clamp-1" title="<?= $stat['name_pro'] ?>"><?= $stat['name_pro'] ?></span>
                            </td>
                            <td class="px-6 py-3 text-center"><span class="bg-amber-100 text-amber-700 px-2.5 py-0.5 rounded-full font-medium"><?= $stat['total_sold'] ?></span></td>
                            <td class="px-6 py-3 text-right font-semibold text-brand-600"><?= number_format($stat['total_revenue']) ?> ₫</td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if(empty($product_sold_stats)): ?>
                        <tr><td colspan="3" class="px-6 py-8 text-center text-slate-500">Không có dữ liệu sản phẩm bán ra.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Inventory Stats -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50 flex justify-between items-center">
        <h2 class="font-semibold text-slate-800"><i class="fas fa-boxes text-blue-500 mr-2"></i>Tình trạng tồn kho (Tất cả sản phẩm)</h2>
        <span class="text-xs font-medium text-slate-500">Sắp xếp: Tồn kho thấp nhất</span>
    </div>
    <div class="p-0 overflow-auto max-h-[500px]">
        <table class="w-full text-left border-collapse text-sm">
            <thead class="sticky top-0 bg-slate-50 z-10 shadow-sm">
                <tr class="text-slate-600 text-xs font-semibold uppercase tracking-wide">
                    <th class="px-6 py-3 border-b border-slate-200">Sản phẩm</th>
                    <th class="px-6 py-3 border-b border-slate-200">Đơn giá</th>
                    <th class="px-6 py-3 border-b border-slate-200 text-center">Tồn kho hiện tại</th>
                    <th class="px-6 py-3 border-b border-slate-200 text-center">Tình trạng</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($inventory_stats as $stat): 
                    $qty = $stat['stock'];
                ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-3 flex items-center gap-3">
                            <img src="./uploads/<?= $stat['img_pro'] ?>" class="w-10 h-10 rounded-md border border-slate-200 object-cover">
                            <span class="font-medium text-slate-800"><?= $stat['name_pro'] ?></span>
                        </td>
                        <td class="px-6 py-3 font-semibold text-slate-700"><?= number_format($stat['price']) ?> ₫</td>
                        <td class="px-6 py-3 text-center font-bold <?= $qty <= 5 ? 'text-red-600' : 'text-slate-700' ?>"><?= $qty ?></td>
                        <td class="px-6 py-3 text-center">
                            <?php if ($qty == 0): ?>
                                <span class="bg-red-100 text-red-700 px-2.5 py-1 rounded-md text-xs font-medium"><i class="fas fa-times-circle mr-1"></i>Hết hàng</span>
                            <?php elseif ($qty <= 5): ?>
                                <span class="bg-orange-100 text-orange-700 px-2.5 py-1 rounded-md text-xs font-medium"><i class="fas fa-exclamation-triangle mr-1"></i>Sắp hết</span>
                            <?php else: ?>
                                <span class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-md text-xs font-medium"><i class="fas fa-check-circle mr-1"></i>Còn hàng</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once "footer.php" ?>
