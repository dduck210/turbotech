<nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pt-6" aria-label="Breadcrumb">
    <ol class="flex items-center gap-2 text-sm text-ink-500">
        <li><a href="index.php" class="hover:text-brand-600 transition-colors">Trang chủ</a></li>
        <li aria-hidden="true">/</li>
        <li class="font-medium text-ink-900" aria-current="page">Đặt hàng</li>
    </ol>
</nav>

<div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-8 md:py-12">
    <h1 class="font-heading text-2xl md:text-3xl font-bold text-ink-900 mb-6">Thông tin đặt hàng</h1>

    <!-- thông tin đặt hàng -->
    <form action="index.php?act=billconfirm" method="post">
        <?php if (isset($_SESSION['user'])) {
            extract($_SESSION['user']); ?>
        <div class="mb-6 rounded-2xl border border-ink-200 bg-white p-6 shadow-sm">
            <h2 class="font-heading text-lg font-semibold text-ink-900 mb-4">Thông tin đặt hàng</h2>
            <div class="space-y-4">
                <div>
                    <label for="bill-user-name" class="block text-sm font-medium text-ink-700 mb-1.5">Tài khoản người dùng</label>
                    <input id="bill-user-name" name="user_name" type="text" value="<?= $user_name ?>" disabled
                        class="block w-full rounded-lg border border-ink-200 bg-ink-50 px-3.5 py-2.5 text-sm font-semibold text-ink-500 cursor-not-allowed" />
                </div>
                <div>
                    <label for="bill-full-name" class="block text-sm font-medium text-ink-700 mb-1.5">Họ tên người đặt</label>
                    <input id="bill-full-name" name="full_name" type="text" placeholder="Nhập họ tên người nhận" value="<?= $full_name ?>" required
                        class="block w-full rounded-lg border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500" />
                </div>
                <div>
                    <label for="bill-address" class="block text-sm font-medium text-ink-700 mb-1.5">Địa chỉ</label>
                    <input id="bill-address" name="address" type="text" placeholder="Nhập địa chỉ nhận hàng" value="<?= $address ?>" required
                        class="block w-full rounded-lg border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500" />
                </div>
                <div>
                    <label for="bill-email" class="block text-sm font-medium text-ink-700 mb-1.5">Email</label>
                    <input id="bill-email" name="email" type="email" placeholder="Nhập email người nhận" value="<?= $email_user ?>" required
                        class="block w-full rounded-lg border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500" />
                </div>
                <div>
                    <label for="bill-phone" class="block text-sm font-medium text-ink-700 mb-1.5">Điện thoại</label>
                    <input id="bill-phone" name="phone" type="text" placeholder="Nhập số điện thoại người nhận" value="<?= $phone_user ?>" required
                        class="block w-full rounded-lg border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500" />
                </div>
                <?php if (isset($_COOKIE['error'])) : ?>
                <p class="rounded-lg border border-green-200 bg-green-50 p-3 text-sm font-semibold text-green-700">
                    <?= $_COOKIE['error'] ?>
                </p>
                <?php endif ?>
            </div>
        </div>
        <!-- phương thức thanh toán -->
        <div class="mb-6 rounded-2xl border border-ink-200 bg-white p-6 shadow-sm">
            <h2 class="font-heading text-lg font-semibold text-ink-900 mb-4">Phương thức thanh toán</h2>
            <div class="flex flex-col sm:flex-row gap-3">
                <label class="flex flex-1 min-h-[44px] items-center gap-3 rounded-lg border border-ink-200 px-4 py-3 cursor-pointer hover:bg-ink-50 has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50 transition-colors">
                    <input class="h-4 w-4 text-brand-600 focus:ring-2 focus:ring-brand-500" type="radio" name="payment" id="inlineRadio1" value="1" checked>
                    <span class="text-sm font-medium text-ink-900">Thanh toán khi nhận hàng</span>
                </label>
                <label class="flex flex-1 min-h-[44px] items-center gap-3 rounded-lg border border-ink-200 px-4 py-3 cursor-pointer hover:bg-ink-50 has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50 transition-colors">
                    <input class="h-4 w-4 text-brand-600 focus:ring-2 focus:ring-brand-500" type="radio" name="payment" id="inlineRadio2" value="2">
                    <span class="text-sm font-medium text-ink-900">Chuyển khoản ngân hàng</span>
                </label>
            </div>
        </div>
        <?php } else { ?>
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-6 text-center">
            <p class="mb-4 text-sm font-semibold text-red-600">Bạn chưa đăng nhập. Hãy đăng nhập tài khoản để tiến hành đặt hàng!</p>
            <a href="index.php?act=login"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">
                <i class="fa-solid fa-right-to-bracket" aria-hidden="true"></i> Đăng nhập
            </a>
        </div>
        <?php } ?>
        <!-- Begin JB's Cart Area -->
        <div class="rounded-2xl border border-ink-200 bg-white shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-ink-50 text-xs uppercase tracking-wide text-ink-500">
                        <tr>
                            <th class="px-4 py-3" scope="col">Hình ảnh</th>
                            <th class="px-4 py-3" scope="col">Sản phẩm</th>
                            <th class="px-4 py-3" scope="col">Đơn giá</th>
                            <th class="px-4 py-3 text-center" scope="col">Số lượng</th>
                            <th class="px-4 py-3" scope="col">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-ink-100">
                        <?php
                        // Inlined from the old `viewcart(0)` helper (`model/giohang.php`),
                        // called here with removecol=0 (no remove column/button).
                        $total_amount = 0;
                        $i = 0;
                        foreach ($_SESSION['mycart'] as $cart) {
                            $img_pro = "admin/uploads/" . $cart[2];
                            $prodetail = "index.php?act=prodetail&idpro=" . $cart[0];
                            $quantity = $cart[4];
                            $total = $cart[3] * $cart[4];
                            $total_amount += $total;
                        ?>
                        <tr>
                            <td class="px-4 py-3">
                                <a href="<?= $prodetail ?>" class="block h-16 w-16 overflow-hidden rounded-lg bg-ink-100">
                                    <img src="<?= $img_pro ?>" alt="<?= $cart[1] ?>" class="h-full w-full object-cover" />
                                </a>
                            </td>
                            <td class="px-4 py-3">
                                <a href="<?= $prodetail ?>" class="font-heading font-semibold text-ink-900 hover:text-brand-600 transition-colors"><?= $cart[1] ?></a>
                            </td>
                            <td class="px-4 py-3 text-ink-700"><?= number_format($cart[3]) ?> ₫</td>
                            <td class="px-4 py-3 text-center text-ink-700"><?= $quantity ?></td>
                            <td class="px-4 py-3 font-semibold text-ink-900"><?= number_format($total) ?> ₫</td>
                        </tr>
                        <?php $i += 1;
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <a href="index.php?act=viewcart"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-ink-200 bg-white px-5 py-2.5 text-sm font-semibold text-ink-900 hover:bg-ink-50 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500">
                Quay lại giỏ hàng
            </a>
        </div>

        <div class="mt-8 flex justify-end">
            <div class="w-full md:w-96 rounded-2xl border border-ink-200 bg-white p-6 shadow-sm">
                <h2 class="font-heading text-lg font-semibold text-ink-900 mb-4">Tổng giỏ hàng</h2>
                <div class="flex items-center justify-between text-sm text-ink-700 mb-6">
                    <span>Tổng thành tiền</span>
                    <span class="text-lg font-bold text-brand-600"><?= number_format($total_amount) ?> ₫</span>
                </div>
                <input type="submit" name="orderconfirm" value="Xác nhận đặt hàng"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 cursor-pointer" />
            </div>
        </div>
    </form>
</div>
