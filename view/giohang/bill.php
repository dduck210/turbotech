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
    <form action="index.php?act=billconfirm" method="post" data-validate novalidate>
        <?php if (isset($_SESSION['user'])) {
            extract($_SESSION['user']); ?>
        <div class="mb-6 rounded-2xl border border-ink-200 bg-ink-800/70 backdrop-blur-xl p-6 shadow-sm">
            <h2 class="font-heading text-lg font-semibold text-ink-900 mb-4">Thông tin đặt hàng</h2>
            <div class="space-y-4">
                <div>
                    <label for="bill-user-name" class="block text-sm font-medium text-ink-700 mb-1.5">Tài khoản người dùng</label>
                    <input id="bill-user-name" name="user_name" type="text" value="<?= $user_name ?>" disabled
                        class="block w-full rounded-lg border border-ink-200 bg-ink-50 px-3.5 py-2.5 text-sm font-semibold text-ink-500 cursor-not-allowed" />
                </div>
                <div>
                    <label for="bill-full-name" class="block text-sm font-medium text-ink-700 mb-1.5">Họ tên người đặt</label>
                    <input id="bill-full-name" name="full_name" type="text" placeholder="Nhập họ tên người nhận" value="<?= $full_name ?>"
                        data-rules="required|min:2|max:100"
                        class="block w-full rounded-lg border border-ink-200 bg-ink-800/70 backdrop-blur-xl px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500" />
                </div>
                <div>
                    <label for="bill-province" class="block text-sm font-medium text-ink-700 mb-1.5">Tỉnh/Thành phố</label>
                    <select id="bill-province" name="province" data-address-province
                        data-existing-address="<?= htmlspecialchars($address ?? '') ?>"
                        data-rules="required" data-msg-required="Vui lòng chọn tỉnh/thành phố"
                        class="block w-full rounded-lg border border-ink-200 bg-ink-800/70 backdrop-blur-xl px-3.5 py-2.5 text-sm text-ink-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                        <option value="">Đang tải danh sách...</option>
                    </select>
                </div>
                <div>
                    <label for="bill-ward" class="block text-sm font-medium text-ink-700 mb-1.5">Xã/Phường</label>
                    <select id="bill-ward" name="ward" data-address-ward
                        data-rules="required" data-msg-required="Vui lòng chọn xã/phường"
                        class="block w-full rounded-lg border border-ink-200 bg-ink-800/70 backdrop-blur-xl px-3.5 py-2.5 text-sm text-ink-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500" disabled>
                        <option value="">Chọn tỉnh/thành phố trước</option>
                    </select>
                </div>
                <div>
                    <label for="bill-address-detail" class="block text-sm font-medium text-ink-700 mb-1.5">Địa chỉ chi tiết</label>
                    <input id="bill-address-detail" name="address_detail" type="text" data-address-detail placeholder="Số nhà, tên đường..."
                        data-rules="required|min:3|max:255"
                        class="block w-full rounded-lg border border-ink-200 bg-ink-800/70 backdrop-blur-xl px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500" />
                </div>
                <div>
                    <label for="bill-email" class="block text-sm font-medium text-ink-700 mb-1.5">Email</label>
                    <input id="bill-email" name="email" type="email" placeholder="Nhập email người nhận" value="<?= $email_user ?>"
                        data-rules="required|email"
                        class="block w-full rounded-lg border border-ink-200 bg-ink-800/70 backdrop-blur-xl px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500" />
                </div>
                <div>
                    <label for="bill-phone" class="block text-sm font-medium text-ink-700 mb-1.5">Điện thoại</label>
                    <input id="bill-phone" name="phone" type="text" placeholder="Nhập số điện thoại người nhận" value="<?= $phone_user ?>"
                        data-rules="required|phone"
                        class="block w-full rounded-lg border border-ink-200 bg-ink-800/70 backdrop-blur-xl px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500" />
                </div>
                <?php if (isset($_COOKIE['error'])) : ?>
                <p class="rounded-lg border border-green-200 bg-green-50 p-3 text-sm font-semibold text-green-700">
                    <?= $_COOKIE['error'] ?>
                </p>
                <?php endif ?>
            </div>
        </div>
        <!-- phương thức thanh toán -->
        <div class="mb-6 rounded-2xl border border-ink-200 bg-ink-800/70 backdrop-blur-xl p-6 shadow-sm">
            <h2 class="font-heading text-lg font-semibold text-ink-900 mb-4">Phương thức thanh toán</h2>
            <div class="flex flex-col sm:flex-row gap-3">
                <label class="flex flex-1 min-h-11 items-center gap-3 rounded-lg border border-ink-200 px-4 py-3 cursor-pointer hover:bg-ink-50 has-checked:border-brand-500 has-checked:bg-brand-50 transition-colors">
                    <input class="h-4 w-4 text-brand-600 focus:ring-2 focus:ring-brand-500" type="radio" name="payment" id="inlineRadio1" value="1" checked>
                    <span class="text-sm font-medium text-ink-900">Thanh toán khi nhận hàng</span>
                </label>
                <label class="flex flex-1 min-h-11 items-center gap-3 rounded-lg border border-ink-200 px-4 py-3 cursor-pointer hover:bg-ink-50 has-checked:border-brand-500 has-checked:bg-brand-50 transition-colors">
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
        <div class="rounded-2xl border border-ink-200 bg-ink-800/70 backdrop-blur-xl shadow-sm overflow-hidden">
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
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-ink-200 bg-ink-800/70 backdrop-blur-xl px-5 py-2.5 text-sm font-semibold text-ink-900 hover:bg-ink-50 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500">
                Quay lại giỏ hàng
            </a>
        </div>

        <div class="mt-8 flex justify-end">
            <div class="w-full md:w-96 rounded-2xl border border-ink-200 bg-ink-800/70 backdrop-blur-xl p-6 shadow-sm">
                <h2 class="font-heading text-lg font-semibold text-ink-900 mb-4">Tổng giỏ hàng</h2>

                <div class="mb-4">
                    <label for="coupon-code" class="block text-sm font-medium text-ink-700 mb-1.5">Mã giảm giá</label>
                    <div class="flex gap-2">
                        <input type="text" id="coupon-code" placeholder="Nhập mã giảm giá"
                            value="<?= htmlspecialchars($couponCode ?? '') ?>"
                            class="block w-full rounded-lg border border-ink-200 bg-ink-800/70 backdrop-blur-xl px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 uppercase" />
                        <button type="button" id="coupon-apply-btn"
                            class="shrink-0 rounded-lg bg-ink-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-ink-800 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500">
                            Áp dụng
                        </button>
                    </div>
                    <p id="coupon-message" class="mt-1.5 text-xs <?= $couponCode ? 'text-emerald-600' : 'hidden' ?>">
                        <?php if ($couponCode) : ?>
                            Đã áp dụng mã <?= htmlspecialchars($couponCode) ?>
                            <button type="button" id="coupon-remove-btn" class="ml-1 font-semibold text-red-600 underline">Xóa mã</button>
                        <?php endif; ?>
                    </p>
                </div>

                <div class="flex items-center justify-between text-sm text-ink-700 mb-1">
                    <span>Tạm tính</span>
                    <span id="coupon-subtotal" class="font-semibold text-ink-900"><?= number_format($total_amount) ?> ₫</span>
                </div>
                <div id="coupon-discount-row" class="flex items-center justify-between text-sm text-emerald-600 mb-1 <?= $couponDiscount > 0 ? '' : 'hidden' ?>">
                    <span>Giảm giá</span>
                    <span id="coupon-discount-value">-<?= number_format($couponDiscount) ?> ₫</span>
                </div>
                <div class="flex items-center justify-between text-sm text-ink-700 mb-6 border-t border-ink-100 pt-3">
                    <span>Tổng thanh toán</span>
                    <span id="coupon-total" class="text-lg font-bold text-brand-600"><?= number_format($total_amount - $couponDiscount) ?> ₫</span>
                </div>
                <input type="submit" name="orderconfirm" value="Xác nhận đặt hàng"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 cursor-pointer" />
            </div>
        </div>
    </form>
</div>

<script>
(function () {
    var subtotal = <?= (int) $total_amount ?>;

    function formatVnd(n) {
        // Comma thousands-separator to match PHP's number_format() used
        // everywhere else on this page (not toLocaleString('vi-VN'), which
        // uses periods and would look inconsistent after an AJAX update).
        return Number(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') + ' ₫';
    }

    function bindRemoveCoupon() {
        var btn = document.getElementById('coupon-remove-btn');
        if (!btn) return;
        btn.addEventListener('click', function () {
            $.ajax({
                url: '?act=removecoupon',
                type: 'POST',
                success: function (data) {
                    var match = /<!--COUPON_RESULT:(.*?):END-->/.exec(data);
                    if (!match) return;
                    document.getElementById('coupon-code').value = '';
                    var msgEl = document.getElementById('coupon-message');
                    msgEl.className = 'mt-1.5 text-xs hidden';
                    msgEl.innerHTML = '';
                    document.getElementById('coupon-discount-row').classList.add('hidden');
                    document.getElementById('coupon-total').textContent = formatVnd(subtotal);
                }
            });
        });
    }

    document.getElementById('coupon-apply-btn')?.addEventListener('click', function () {
        var code = document.getElementById('coupon-code').value.trim();
        $.ajax({
            url: '?act=applycoupon',
            type: 'POST',
            data: { coupon_code: code },
            success: function (data) {
                var match = /<!--COUPON_RESULT:(.*?):END-->/.exec(data);
                if (!match) return;
                var result = JSON.parse(match[1]);
                var msgEl = document.getElementById('coupon-message');

                if (result.success) {
                    msgEl.className = 'mt-1.5 text-xs text-emerald-600';
                    msgEl.innerHTML = 'Đã áp dụng mã ' + result.code +
                        ' <button type="button" id="coupon-remove-btn" class="ml-1 font-semibold text-red-600 underline">Xóa mã</button>';
                    document.getElementById('coupon-discount-row').classList.remove('hidden');
                    document.getElementById('coupon-discount-value').textContent = '-' + formatVnd(result.discount);
                    document.getElementById('coupon-total').textContent = formatVnd(result.total);
                    bindRemoveCoupon();
                } else {
                    msgEl.className = 'mt-1.5 text-xs text-red-600';
                    msgEl.textContent = result.message;
                }
            },
            error: function () {
                alert('Không thể áp dụng mã giảm giá, vui lòng thử lại!');
            }
        });
    });

    bindRemoveCoupon();
})();
</script>
