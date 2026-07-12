<?php include_once "header.php" ?>
<?php /** @var array $one_bill */ ?>


<?php
if (is_array($one_bill)) {
    extract($one_bill);
}
?>
<div class="mb-8 flex items-center justify-between">
    <h1 class="text-3xl font-bold text-ink-800">Cập nhật hóa đơn</h1>
</div>

<div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden mb-6">
    <div class="p-6">
        <div class="form-addcate">
            <form action="./index.php?act=update_bill" method="post">
<?= \Codemoi\Core\Csrf::field() ?>
                <div class="mb-4">
                    <label for="formGroupExampleInput" class="block text-sm font-medium text-ink-700 mb-1">Mã hóa
                        đơn</label>
                    <input type="text" name="id_bill"
                        class="w-full rounded-lg border border-ink-200 px-4 py-2 focus:ring-2 focus:ring-brand-500 outline-none transition-all bg-ink-50"
                        value="<?= e($id_bill) ?>" disabled>
                </div>
                <div class="mb-4">
                    <label for="formGroupExampleInput" class="block text-sm font-medium text-ink-700 mb-1">Người
                        đặt</label>
                    <input type="text" name="user_name"
                        class="w-full rounded-lg border border-ink-200 px-4 py-2 focus:ring-2 focus:ring-brand-500 outline-none transition-all bg-ink-50"
                        placeholder="Mã sản phẩm" value="<?= e($user_name) ?>" disabled>
                </div>
                <div class="mb-4">
                    <label for="formGroupExampleInput" class="block text-sm font-medium text-ink-700 mb-1">Địa chỉ
                        nhận hàng</label>
                    <input type="text" name="user_name"
                        class="w-full rounded-lg border border-ink-200 px-4 py-2 focus:ring-2 focus:ring-brand-500 outline-none transition-all bg-ink-50"
                        placeholder="Mã sản phẩm" value="<?= e($address) ?>" disabled>
                </div>
                <div class="mb-4">
                    <label for="formGroupExampleInput" class="block text-sm font-medium text-ink-700 mb-1">Ngày
                        đặt</label>
                    <input type="text" name="order_date"
                        class="w-full rounded-lg border border-ink-200 px-4 py-2 focus:ring-2 focus:ring-brand-500 outline-none transition-all bg-ink-50"
                        placeholder="Ngày đặt hàng" value="<?= e($order_date) ?>" disabled>
                </div>
                <div class="mb-4">
                    <label for="formGroupExampleInput" class="block text-sm font-medium text-ink-700 mb-1">Thành
                        tiền</label>
                    <input type="text" name="total_amount"
                        class="w-full rounded-lg border border-ink-200 px-4 py-2 focus:ring-2 focus:ring-brand-500 outline-none transition-all bg-ink-50"
                        placeholder="Tổng thành tiền sản phẩm" value="<?= number_format($total_amount) ?>" disabled>
                </div>
                <div class="mb-4">
                    <label for="formGroupExampleInput" class="block text-sm font-medium text-ink-700 mb-1">Phương thức
                        thanh toán</label>
                    <input type="text" name="payment"
                        class="w-full rounded-lg border border-ink-200 px-4 py-2 focus:ring-2 focus:ring-brand-500 outline-none transition-all bg-ink-50"
                        placeholder="Phương thức thanh toán" value="<?php if ($payment == 1) {
                                                                        echo "Thanh toán khi nhận hàng";
                                                                    } else if ($payment == 2) {
                                                                        echo "Chuyển khoản ngân hàng";
                                                                    } else if ($payment == 3) {
                                                                        echo "Thanh toán online";
                                                                    } else {
                                                                        echo "Không tìm thấy phương thức thanh toán";
                                                                    }  ?>" disabled>
                </div>
                <div class="mb-4">
                    <label for="formGroupExampleInput" class="block text-sm font-medium text-ink-700 mb-1">Thanh
                        toán</label>
                    <select required
                        class="w-full rounded-lg border border-ink-200 px-4 py-2 focus:ring-2 focus:ring-brand-500 outline-none transition-all bg-white"
                        name="status_pay" id="">
                        <option value="0" <?= $status_pay == 0 ? "selected" : "" ?>>Chưa thanh toán</option>
                        <option value="1" <?= $status_pay == 1 ? "selected" : "" ?>>Đã thanh toán</option>

                    </select>
                </div>
                <div class="mb-4">
                    <label for="formGroupExampleInput" class="block text-sm font-medium text-ink-700 mb-1">Trạng
                        thái</label>
                    <select required
                        class="w-full rounded-lg border border-ink-200 px-4 py-2 focus:ring-2 focus:ring-brand-500 outline-none transition-all bg-white"
                        name="status" id="">
                        <option value="0" <?= $status == 0 ? "selected" : "" ?>>Đơn hàng mới</option>
                        <option value="1" <?= $status == 1 ? "selected" : "" ?>>Đang xử lý</option>
                        <option value="2" <?= $status == 2 ? "selected" : "" ?>>Đang giao hàng</option>
                        <option value="3" <?= $status == 3 ? "selected" : "" ?>>Đã giao hàng</option>
                        <option value="4" <?= $status == 4 ? "selected" : "" ?>>Đã hủy</option>

                    </select>
                </div>

                <div class="wrap-btn mt-4">
                    <input type="hidden" name="id_bill" value="<?= e($id_bill) ?>">
                    <input type="submit" name="btn_update"
                        class="bg-brand-600 hover:bg-brand-700 text-white font-medium rounded-lg px-4 py-2 transition-all active:scale-[0.97] inline-block"
                        value="Cập nhật">
                    <input type="reset"
                        class="bg-red-500 hover:bg-red-600 text-white rounded-lg px-3 py-1.5 inline-block ml-2"
                        value="Nhập lại">
                </div>
            </form>
        </div>
    </div>
</div>
<div class="bg-white rounded-xl shadow-sm border border-ink-200 overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-ink-200 bg-ink-50/50 font-semibold text-ink-800">
        <h6 class="m-0">Sản phẩm</h6>
    </div>
    <div class="p-6 overflow-x-auto">
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
                        <td class="px-4 py-3"><a href=""
                                class="text-brand-600 hover:text-brand-700"><?= e($value['name_pro']) ?></a></td>
                        <td class="px-4 py-3"><span class="amount"><?= number_format($value['price_pro']) ?> ₫</span></td>
                        <td class="px-4 py-3 quantity"><?= e($value['quantity']) ?></td>
                        <td class="px-4 py-3 product-subtotal"><span
                                class="amount text-emerald-600 font-medium"><?= number_format($value['total_amount']) ?>₫</span></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>


<?php include_once "footer.php" ?>