<?php

/**
 * @var array|false $bill        Bill row (Codemoi\Model\Order::one()).
 * @var array        $cart_detail Persisted cart lines (Codemoi\Model\Order::items()).
 */
?>
<nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pt-6" aria-label="Breadcrumb">
    <ol class="flex items-center gap-2 text-sm text-ink-500">
        <li><a href="index.php" class="hover:text-brand-600 transition-colors">Trang chủ</a></li>
        <li aria-hidden="true">/</li>
        <li class="font-medium text-ink-900" aria-current="page">Đặt hàng</li>
    </ol>
</nav>

<div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-8 md:py-12">
    <div class="mb-6 rounded-lg border border-green-500/30 bg-green-500/10 p-4 text-sm font-semibold text-green-700">
        Đặt hàng thành công! Cảm ơn quý khách đã mua hàng của Turbotech
    </div>
    <?php
    if (isset($bill) && is_array($bill)) {
        extract($bill);
    }
    ?>
    <!-- mã đơn hàng -->
    <div class="mb-6 rounded-2xl border border-ink-200 bg-ink-200/70 backdrop-blur-xl p-6 shadow-sm">
        <h2 class="font-heading text-lg font-semibold text-ink-900 mb-4">Thông tin đơn hàng</h2>
        <dl class="space-y-2 text-sm">
            <div class="flex items-center justify-between">
                <dt class="text-ink-500">Mã đơn hàng</dt>
                <dd class="font-semibold text-ink-900">UTP-<?= e($id_bill) ?></dd>
            </div>
            <div class="flex items-center justify-between">
                <dt class="text-ink-500">Thời gian đặt hàng</dt>
                <dd class="font-semibold text-ink-900"><?= e($order_date) ?></dd>
            </div>
            <?php if (!empty($coupon_code)) : ?>
                <div class="flex items-center justify-between">
                    <dt class="text-ink-500">Mã giảm giá</dt>
                    <dd class="font-semibold text-emerald-600"><?= htmlspecialchars($coupon_code) ?> (-<?= number_format($discount_amount) ?>₫)</dd>
                </div>
            <?php endif; ?>
            <div class="flex items-center justify-between">
                <dt class="text-ink-500">Tổng thành tiền</dt>
                <dd class="font-semibold text-ink-900"><?= number_format($total_amount) ?>₫</dd>
            </div>
            <div class="flex items-center justify-between">
                <dt class="text-ink-500">Trạng thái</dt>
                <dd class="font-semibold text-ink-900">
                    <?php if ($status_pay == 1) {
                        echo "Đã thanh toán";
                    } else {
                        echo "Chưa thanh toán";
                    } ?>
                </dd>
            </div>
        </dl>
    </div>
    <!-- thông tin đặt hàng -->
    <form action="index.php?act=billconfirm" method="post">
<?= \Codemoi\Core\Csrf::field() ?>
        <div class="mb-6 rounded-2xl border border-ink-200 bg-ink-200/70 backdrop-blur-xl p-6 shadow-sm">
            <h2 class="font-heading text-lg font-semibold text-ink-900 mb-4">Thông tin đặt hàng</h2>
            <div class="space-y-4">
                <div>
                    <label for="billconfirm-user-name" class="block text-sm font-medium text-ink-700 mb-1.5">Tài khoản người dùng</label>
                    <input id="billconfirm-user-name" name="" type="text" value="<?= e($user_name) ?>" disabled
                        class="block w-full rounded-lg border border-ink-200 bg-ink-50 px-3.5 py-2.5 text-sm font-semibold text-ink-500 cursor-not-allowed" />
                </div>
                <div>
                    <label for="billconfirm-full-name" class="block text-sm font-medium text-ink-700 mb-1.5">Họ tên người đặt</label>
                    <input id="billconfirm-full-name" name="full_name" type="text" value="<?= e($full_name) ?>" readonly
                        class="block w-full rounded-lg border border-ink-200 bg-ink-50 px-3.5 py-2.5 text-sm font-semibold text-ink-900" />
                </div>
                <div>
                    <label for="billconfirm-address" class="block text-sm font-medium text-ink-700 mb-1.5">Địa chỉ</label>
                    <input id="billconfirm-address" name="address" type="text" value="<?= e($address) ?>" readonly
                        class="block w-full rounded-lg border border-ink-200 bg-ink-50 px-3.5 py-2.5 text-sm font-semibold text-ink-900" />
                </div>
                <div>
                    <label for="billconfirm-email" class="block text-sm font-medium text-ink-700 mb-1.5">Email</label>
                    <input id="billconfirm-email" name="email" type="email" value="<?= e($email) ?>" readonly
                        class="block w-full rounded-lg border border-ink-200 bg-ink-50 px-3.5 py-2.5 text-sm font-semibold text-ink-900" />
                </div>
                <div>
                    <label for="billconfirm-phone" class="block text-sm font-medium text-ink-700 mb-1.5">Điện thoại</label>
                    <input id="billconfirm-phone" name="phone" type="text" value="0<?= e($phone) ?>" readonly
                        class="block w-full rounded-lg border border-ink-200 bg-ink-50 px-3.5 py-2.5 text-sm font-semibold text-ink-900" />
                </div>
            </div>
        </div>
        <!-- phương thức thanh toán -->
        <div class="mb-6 rounded-2xl border border-ink-200 bg-ink-200/70 backdrop-blur-xl p-6 shadow-sm">
            <h2 class="font-heading text-lg font-semibold text-ink-900 mb-4">Phương thức thanh toán</h2>
            <label class="flex min-h-11 items-center gap-3 rounded-lg border border-ink-200 px-4 py-3">
                <input class="h-4 w-4 text-brand-600 focus:ring-2 focus:ring-brand-500" type="radio" name="payment" id="inlineRadio1" checked>
                <span class="text-sm font-semibold text-brand-600">
                    <?php if ($payment == 1) {
                        echo "Thanh toán khi nhận hàng ";
                    } else if ($payment == 2) {
                        echo "Chuyển khoản ngân hàng";
                    } else if ($payment == 3) {
                        echo "Thanh toán Online";
                    } else {
                        echo "Không tìm thấy phương thức thanh toán";
                    } ?>
                </span>
            </label>
        </div>
        <!-- Begin JB's Cart Area -->
        <?php include __DIR__ . '/cart-detail-table.php'; ?>

        <div class="mt-6 flex justify-end">
            <a href="index.php?act=product"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-ink-200 bg-ink-200/70 backdrop-blur-xl px-5 py-2.5 text-sm font-semibold text-ink-900 hover:bg-ink-50 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500">
                Xem thêm sản phẩm <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
            </a>
        </div>
    </form>
</div>