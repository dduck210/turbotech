<?php include_once "header.php" ?>
<?php /** @var array $one_bill */ ?>


<?php
if (is_array($one_bill)) {
    extract($one_bill);
}
?>
<div class="mb-8 pb-5 border-b border-ink-300">
    <div class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600 mb-1">Quản lý</div>
    <h1 class="font-heading text-3xl text-ink-900">Cập nhật hóa đơn</h1>
</div>

<div class="card-boutique rounded-lg overflow-hidden mb-6">
    <div class="p-6">
        <div class="form-addcate">
            <form action="./index.php?act=update_bill" method="post">
<?= \Codemoi\Core\Csrf::field() ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="mb-4">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Mã hóa
                            đơn</label>
                        <input type="text" name="id_bill"
                            class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-100 text-ink-600"
                            value="<?= e($id_bill) ?>" disabled>
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Người
                            đặt</label>
                        <input type="text" name="user_name"
                            class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-100 text-ink-600"
                            value="<?= e($user_name) ?>" disabled>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="mb-4">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Địa chỉ
                            nhận hàng</label>
                        <input type="text" name="address"
                            class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-100 text-ink-600"
                            value="<?= e($address) ?>" disabled>
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Ngày
                            đặt</label>
                        <input type="text" name="order_date"
                            class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-100 text-ink-600"
                            value="<?= e($order_date) ?>" disabled>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="mb-4">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Thành
                            tiền</label>
                        <input type="text" name="total_amount"
                            class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-100 text-ink-600"
                            value="<?= number_format($total_amount) ?>" disabled>
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Phương thức
                            thanh toán</label>
                        <input type="text" name="payment"
                            class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-100 text-ink-600"
                            value="<?php if ($payment == 1) {
                                                                            echo "Thanh toán khi nhận hàng";
                                                                        } else if ($payment == 2) {
                                                                            echo "Chuyển khoản ngân hàng";
                                                                        } else if ($payment == 3) {
                                                                            echo "Thanh toán online";
                                                                        } else {
                                                                            echo "Không tìm thấy phương thức thanh toán";
                                                                        }  ?>" disabled>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="mb-4">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Thanh
                            toán</label>
                        <select required
                            class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800"
                            name="status_pay" id="">
                            <option value="0" <?= $status_pay == 0 ? "selected" : "" ?>>Chưa thanh toán</option>
                            <option value="1" <?= $status_pay == 1 ? "selected" : "" ?>>Đã thanh toán</option>

                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Trạng
                            thái</label>
                        <select required
                            class="w-full rounded-md border border-ink-300 px-4 py-2.5 focus:ring-2 focus:ring-brand-400 outline-none transition-all bg-ink-50 text-ink-800"
                            name="status" id="">
                            <option value="0" <?= $status == 0 ? "selected" : "" ?>>Đơn hàng mới</option>
                            <option value="1" <?= $status == 1 ? "selected" : "" ?>>Đang xử lý</option>
                            <option value="2" <?= $status == 2 ? "selected" : "" ?>>Đang giao hàng</option>
                            <option value="3" <?= $status == 3 ? "selected" : "" ?>>Đã giao hàng</option>
                            <option value="4" <?= $status == 4 ? "selected" : "" ?>>Đã hủy</option>

                        </select>
                    </div>
                </div>

                <div class="flex items-center gap-3 mt-6 pt-6 border-t border-ink-200">
                    <input type="hidden" name="id_bill" value="<?= e($id_bill) ?>">
                    <input type="submit" name="btn_update"
                        class="btn-boutique rounded-md px-6 py-2.5 font-medium cursor-pointer"
                        value="Cập nhật">
                    <input type="reset"
                        class="border border-ink-300 text-ink-700 hover:bg-ink-200 rounded-md px-6 py-2.5 transition-colors cursor-pointer"
                        value="Nhập lại">
                </div>
            </form>
        </div>
    </div>
</div>
<div class="card-boutique rounded-lg overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-ink-300">
        <h2 class="font-heading text-lg text-ink-900">Sản phẩm</h2>
        <span class="block w-8 h-px bg-brand-500 mt-2"></span>
    </div>
    <div class="p-6 overflow-x-auto">
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
