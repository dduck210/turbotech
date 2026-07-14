<?php include_once "header.php" ?>
<?php /** @var array $one_bill */ ?>
<?php
if (is_array($one_bill)) {
    extract($one_bill);
}
?>

<div class="mb-8 pb-5 border-b border-ink-300 flex flex-wrap items-end justify-between gap-4">
    <div>
        <div class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600 mb-1">Đơn hàng</div>
        <h1 class="font-heading text-3xl text-ink-900">Chi tiết UTP-<?= e($id_bill) ?></h1>
    </div>
    <a href="index.php?act=listbill"
        class="inline-flex items-center gap-2 border border-ink-300 text-ink-700 font-medium rounded-md px-5 py-2.5 hover:bg-ink-200 transition-colors">
        <i class="fa-solid fa-arrow-left"></i> Quay lại danh sách
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2 card-boutique rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-ink-300">
            <h2 class="font-heading text-lg text-ink-900">Thông tin đơn hàng</h2>
            <span class="block w-8 h-px bg-brand-500 mt-2"></span>
        </div>
        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
            <div>
                <div class="text-xs font-semibold uppercase tracking-wider text-ink-500 mb-1">Người đặt</div>
                <div class="text-ink-800 font-medium"><?= e($user_name) ?></div>
            </div>
            <div>
                <div class="text-xs font-semibold uppercase tracking-wider text-ink-500 mb-1">Họ tên</div>
                <div class="text-ink-800 font-medium"><?= e($full_name) ?></div>
            </div>
            <div>
                <div class="text-xs font-semibold uppercase tracking-wider text-ink-500 mb-1">Email</div>
                <div class="text-ink-800"><?= e($email) ?></div>
            </div>
            <div>
                <div class="text-xs font-semibold uppercase tracking-wider text-ink-500 mb-1">Số điện thoại</div>
                <div class="text-ink-800">0<?= e($phone) ?></div>
            </div>
            <div class="sm:col-span-2">
                <div class="text-xs font-semibold uppercase tracking-wider text-ink-500 mb-1">Địa chỉ nhận hàng</div>
                <div class="text-ink-800"><?= e($address) ?></div>
            </div>
            <div>
                <div class="text-xs font-semibold uppercase tracking-wider text-ink-500 mb-1">Ngày đặt</div>
                <div class="text-ink-800"><?= e($order_date) ?></div>
            </div>
            <?php if (!empty($coupon_code)) : ?>
            <div>
                <div class="text-xs font-semibold uppercase tracking-wider text-ink-500 mb-1">Mã giảm giá</div>
                <div class="text-emerald-600 font-bold"><?= e($coupon_code) ?> (-<?= number_format($discount_amount) ?>₫)</div>
            </div>
            <?php endif; ?>
            <div>
                <div class="text-xs font-semibold uppercase tracking-wider text-ink-500 mb-1">Phương thức thanh toán</div>
                <div class="text-brand-600 font-semibold"><?php if ($payment == 1) {
                                                        echo "Thanh toán khi nhận hàng";
                                                    } else if ($payment == 2) {
                                                        echo "Chuyển khoản ngân hàng";
                                                    } else if ($payment == 3) {
                                                        echo "Thanh toán online";
                                                    } else {
                                                        echo "Không tìm thấy phương thức thanh toán";
                                                    } ?></div>
            </div>
            <div>
                <div class="text-xs font-semibold uppercase tracking-wider text-ink-500 mb-1">Trạng thái đơn hàng</div>
                <div class="text-amber-600 font-semibold"><?php if ($status == 0) {
                                                                            echo "Đơn hàng mới";
                                                                        } else if ($status == 1) {
                                                                            echo "Đang xử lý";
                                                                        } else if ($status == 2) {
                                                                            echo "Đang giao hàng";
                                                                        } else if ($status == 3) {
                                                                            echo "Đã giao hàng";
                                                                        } else if ($status == 4) {
                                                                            echo "Đã hủy";
                                                                        } else {
                                                                            echo "Lỗi trạng thái";
                                                                        } ?></div>
            </div>
            <div>
                <div class="text-xs font-semibold uppercase tracking-wider text-ink-500 mb-1">Trạng thái thanh toán</div>
                <div><?php if ($status_pay == 0) {
                                        echo '<span class="text-red-600 font-semibold">Chưa thanh toán</span>';
                                    } else if ($status_pay == 1) {
                                        echo '<span class="text-emerald-600 font-semibold">Đã thanh toán</span>';
                                    } else {
                                        echo "Không tìm thấy phương thức thanh toán";
                                    }  ?></div>
            </div>
        </div>
    </div>

    <div class="card-boutique rounded-lg overflow-hidden flex flex-col items-center justify-center p-8 text-center">
        <span class="w-10 h-px bg-brand-500 mb-4"></span>
        <div class="text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Thành tiền</div>
        <div class="font-heading text-3xl text-ink-900"><?= number_format($total_amount) ?>₫</div>
    </div>
</div>

<div class="card-boutique rounded-lg overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-ink-300">
        <h2 class="font-heading text-lg text-ink-900">Sản phẩm</h2>
        <span class="block w-8 h-px bg-brand-500 mt-2"></span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-ink-600">
            <thead>
                <tr class="border-b-2 border-ink-300 text-ink-600 text-xs font-semibold tracking-widest uppercase">
                    <th class="px-4 py-3">Hình ảnh</th>
                    <th class="px-4 py-3">Sản phẩm</th>
                    <th class="px-4 py-3">Đơn giá</th>
                    <th class="px-4 py-3">Số lượng</th>
                    <th class="px-4 py-3">Thành tiền</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink-200">
                <?php $id = $_GET['idbill'];
                $bill = \Codemoi\Model\Order::items((int) $id);
                foreach ($bill as $value) {
                ?>
                    <tr class="hover:bg-ink-100/60 transition-colors">
                        <td class="px-4 py-3"><img src="../admin/uploads/<?= e($value['img_pro']) ?>" alt="<?= e($value['name_pro']) ?>"
                                class="w-16 h-16 rounded-md border border-ink-300 object-cover"></td>
                        <td class="px-4 py-3"><?= e($value['name_pro']) ?></td>
                        <td class="px-4 py-3"><span class="amount"><?= number_format($value['price_pro']) ?>₫</span></td>
                        <td class="px-4 py-3 quantity"><?= e($value['quantity']) ?></td>
                        <td class="px-4 py-3 product-subtotal"><span class="amount text-emerald-600 font-medium"><?= number_format($value['total_amount']) ?>₫</span></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once "footer.php" ?>
