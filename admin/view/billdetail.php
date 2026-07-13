<?php include_once "header.php" ?>
<?php /** @var array $one_bill */ ?>
<?php
if (is_array($one_bill)) {
    extract($one_bill);
}
?>


<div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg mb-4 font-semibold">
    Chi tiết đơn hàng UTP-<?= e($id_bill) ?>
</div>
<div class="bg-ink-50 rounded-xl shadow-sm border border-ink-200 overflow-hidden mb-6 p-6">
    <div class="space-y-2 text-ink-700">
        <p>Mã hóa đơn: UTP-<?= e($id_bill) ?>
        </p>
        <p>Người đặt: <span class="fw-bold">
                <?= e($user_name) ?>
            </span></p>
        <p>Họ tên: <?= e($full_name) ?>
        </p>
        <p>Email: <?= e($email) ?></p>
        <p>Địa chỉ nhận hàng: <?= e($address) ?>
        </p>
        <p>Số điện thoại: 0<?= e($phone) ?></p>
        <p>Ngày đặt: <?= e($order_date) ?>
        </p>
        <?php if (!empty($coupon_code)) : ?>
            <p>Mã giảm giá: <span class="text-emerald-600 font-bold"><?= e($coupon_code) ?>
                    (-<?= number_format($discount_amount) ?>₫)</span></p>
        <?php endif; ?>
        <p>Thành tiền: <span class="text-ink-800 font-bold">
                <?= number_format($total_amount) ?>₫
            </span></p>
        <p>Phương thức thanh toán:
            <span class="text-brand-600 font-bold"> <?php if ($payment == 1) {
                                                        echo "Thanh toán khi nhận hàng";
                                                    } else if ($payment == 2) {
                                                        echo "Chuyển khoản ngân hàng";
                                                    } else if ($payment == 3) {
                                                        echo "Thanh toán online";
                                                    } else {
                                                        echo "Không tìm thấy phương thức thanh toán";
                                                    } ?></span>
        </p>
        <p>Trạng thái đơn hàng: <span class="text-amber-500 font-bold"><?php if ($status == 0) {
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
                                                                        } ?>
            </span>
        </p>
        <p>Trạng thái thanh toán: <?php if ($status_pay == 0) {
                                        echo '<span class="text-red-500 font-semibold">Chưa thanh toán</span>';
                                    } else if ($status_pay == 1) {
                                        echo '<span class="text-emerald-500 font-semibold">Đã thanh toán</span>';
                                    } else {
                                        echo "Không tìm thấy phương thức thanh toán";
                                    }  ?></p>
    </div>
</div>

<div class="bg-ink-800/70 backdrop-blur-xl rounded-xl shadow-sm border border-ink-200 overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-ink-600">
            <thead class="bg-ink-50 text-ink-700 uppercase text-xs font-semibold">
                <tr>
                    <th class="px-4 py-3">Hình ảnh</th>
                    <th class="px-4 py-3">Sản phẩm</th>
                    <th class="px-4 py-3">Đơn giá</th>
                    <th class="px-4 py-3">Số lượng</th>
                    <th class="px-4 py-3">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php $id = $_GET['idbill'];
                $bill = \Codemoi\Model\Order::items((int) $id);
                // var_dump($bill);
                foreach ($bill as $value) {
                ?>
                    <tr class="border-b border-ink-100 hover:bg-ink-50 transition-colors">
                        <td class="px-4 py-3"><img src="../admin/uploads/<?= e($value['img_pro']) ?>" alt="Ultraphone Product"
                                width="80px"></img></td>
                        <td class="px-4 py-3">
                            <?= e($value['name_pro']) ?>
                        </td>
                        <td class="px-4 py-3"><span class="amount">
                                <?= number_format($value['price_pro']) ?>₫
                            </span></td>
                        <td class="px-4 py-3 quantity">
                            <?= e($value['quantity']) ?>
                        </td>
                        <td class="px-4 py-3 product-subtotal"><span class="amount text-emerald-600 font-medium">
                                <?= number_format($value['total_amount']) ?>₫
                            </span></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<div class="btn-function mb-6">
    <a href="index.php?act=listbill"
        class="bg-brand-600 hover:bg-brand-700 text-white font-medium rounded-lg px-4 py-2 transition-colors inline-block"><i
            class="fa-solid fa-arrow-left"></i> Quay lại
        danh sách</a>
</div>
</div>

<?php include_once "footer.php" ?>