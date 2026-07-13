<?php
/**
 * @var array       $list_mybill    Orders placed by the logged-in user (Codemoi\Model\Order::allByUser()).
 * @var string|null $cancelMessage  Flash message set after a cancel-order attempt, if any.
 */
?>
<!-- Breadcrumb -->
<div class="border-b border-ink-200 bg-ink-800/70 backdrop-blur-xl">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-3">
        <nav aria-label="Breadcrumb" class="text-sm text-ink-500">
            <ol class="flex items-center gap-2">
                <li><a href="index.php" class="hover:text-brand-600">Trang chủ</a></li>
                <li aria-hidden="true">/</li>
                <li class="font-medium text-ink-900" aria-current="page">Thông tin tài khoản</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Trang chi tiết tài khoản -->
<main class="page-content bg-ink-50">
    <?php if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
        extract($_SESSION['user']);
    } ?>
    <!-- NOTE: intentionally no page-wide <form> here — it used to wrap this whole tab
         area, and every per-order "Hủy đơn" <form> inside the Orders tab table ended up
         nested inside it. Nested <form> elements are invalid HTML; the browser silently
         drops the first nested <form> start tag (its inputs get swallowed into the outer
         form) and its stray </form> end tag then force-closes the outer form early, so
         only the FIRST cancellable order's button actually worked, and only inconsistently.
         Each of "Thông tin tài khoản" / "Mật khẩu" below now has its OWN scoped <form>
         instead (see #account-details / #account-password), so nothing here wraps the
         Orders tab anymore. -->
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12 md:py-16">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-4">

                <!-- Sidebar nav -->
                <div class="lg:col-span-1">
                    <nav aria-label="Điều hướng tài khoản" class="rounded-2xl border border-ink-200 bg-ink-800/70 backdrop-blur-xl shadow-sm overflow-hidden">
                        <ul class="divide-y divide-ink-200" id="account-page-tab" role="tablist">
                            <li>
                                <a href="#account-dashboard" data-tab-target="account-dashboard" role="tab" aria-selected="true"
                                    class="account-tab-trigger flex items-center gap-3 px-4 py-3.5 text-sm font-semibold text-brand-600 bg-brand-50 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-inset">
                                    <i class="fa-solid fa-gauge" aria-hidden="true"></i> Bảng điều khiển
                                </a>
                            </li>
                            <li>
                                <a href="#account-orders" data-tab-target="account-orders" role="tab" aria-selected="false"
                                    class="account-tab-trigger flex items-center gap-3 px-4 py-3.5 text-sm font-semibold text-ink-700 hover:bg-ink-50 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-inset">
                                    <i class="fa-solid fa-box" aria-hidden="true"></i> Đơn hàng
                                </a>
                            </li>
                            <li>
                                <a href="#account-address" data-tab-target="account-address" role="tab" aria-selected="false"
                                    class="account-tab-trigger flex items-center gap-3 px-4 py-3.5 text-sm font-semibold text-ink-700 hover:bg-ink-50 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-inset">
                                    <i class="fa-solid fa-location-dot" aria-hidden="true"></i> Địa chỉ
                                </a>
                            </li>
                            <li>
                                <a href="#account-details" data-tab-target="account-details" role="tab" aria-selected="false"
                                    class="account-tab-trigger flex items-center gap-3 px-4 py-3.5 text-sm font-semibold text-ink-700 hover:bg-ink-50 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-inset">
                                    <i class="fa-solid fa-user" aria-hidden="true"></i> Thông tin tài khoản
                                </a>
                            </li>
                            <li>
                                <a href="#account-password" data-tab-target="account-password" role="tab" aria-selected="false"
                                    class="account-tab-trigger flex items-center gap-3 px-4 py-3.5 text-sm font-semibold text-ink-700 hover:bg-ink-50 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-inset">
                                    <i class="fa-solid fa-lock" aria-hidden="true"></i> Mật khẩu
                                </a>
                            </li>
                            <li>
                                <a href="index.php?act=logout" role="tab" aria-selected="false"
                                    data-confirm="Bạn chắc chắc muốn đăng xuất tài khoản?"
                                    class="flex items-center gap-3 px-4 py-3.5 text-sm font-semibold text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-inset">
                                    <i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i> Đăng xuất
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>

                <!-- Tab content -->
                <div class="lg:col-span-3">
                    <div id="account-page-tab-content">

                        <!-- Dashboard -->
                        <div id="account-dashboard" data-tab-panel role="tabpanel" aria-labelledby="account-dashboard-tab"
                            class="account-tab-panel rounded-2xl border border-ink-200 bg-ink-800/70 backdrop-blur-xl shadow-sm p-6">
                            <p class="text-ink-700">Xin chào, <b class="text-ink-900"><?= $_SESSION['user']['full_name'] ?></b>
                                (không phải <?= $_SESSION['user']['full_name'] ?>?
                                <a href="index.php?act=logout" data-confirm="Bạn chắc chắc muốn đăng xuất tài khoản?"
                                    class="font-semibold text-brand-600 hover:text-brand-700">Đăng xuất</a>)</p>
                            <p class="mt-3 text-ink-700">Từ bảng điều khiển tài khoản của mình, bạn có thể xem các đơn đặt hàng gần
                                đây, quản lý địa chỉ giao hàng và thanh toán cũng như chỉnh sửa mật khẩu và thông tin chi tiết tài
                                khoản của mình.</p>
                        </div>

                        <!-- Orders -->
                        <div id="account-orders" data-tab-panel role="tabpanel" aria-labelledby="account-orders-tab"
                            class="account-tab-panel hidden rounded-2xl border border-ink-200 bg-ink-800/70 backdrop-blur-xl shadow-sm p-6">
                            <h2 class="mb-4 font-heading text-lg font-semibold text-ink-900">Đơn hàng của tôi</h2>
                            <?php if (!empty($cancelMessage)) : ?>
                                <div class="mb-4 rounded-lg border border-ink-200 bg-ink-50 p-3 text-sm text-ink-700"><?= htmlspecialchars($cancelMessage) ?></div>
                            <?php endif; ?>
                            <?php if (empty($list_mybill)) : ?>
                                <div class="py-16 text-center">
                                    <i class="fa-solid fa-box-open text-5xl text-ink-300" aria-hidden="true"></i>
                                    <p class="mt-4 font-heading font-semibold text-ink-900">Chưa có đơn hàng nào</p>
                                    <p class="mt-1 text-sm text-ink-500">Đơn hàng của bạn sẽ hiển thị tại đây.</p>
                                </div>
                            <?php else : ?>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-ink-200 text-sm">
                                        <thead class="bg-ink-50">
                                            <tr>
                                                <th class="px-4 py-3 text-left font-semibold text-ink-700">Mã đơn hàng</th>
                                                <th class="px-4 py-3 text-left font-semibold text-ink-700">Ngày đặt hàng</th>
                                                <th class="px-4 py-3 text-left font-semibold text-ink-700">Số lượng</th>
                                                <th class="px-4 py-3 text-left font-semibold text-ink-700">Trạng thái</th>
                                                <th class="px-4 py-3 text-left font-semibold text-ink-700">Tổng tiền</th>
                                                <th class="px-4 py-3 text-left font-semibold text-ink-700">Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-ink-200">
                                            <?php
                                            foreach ($list_mybill as $bill) :
                                                extract($bill);
                                                $stt = \Codemoi\Model\Order::statusLabel($status);
                                                $countpro = \Codemoi\Model\Order::itemCount($id_bill);
                                            ?>
                                                <tr class="hover:bg-ink-50">
                                                    <td class="px-4 py-3"><a class="font-medium text-brand-600 hover:text-brand-700" href="#">UTP-<?= $id_bill ?></a></td>
                                                    <td class="px-4 py-3 text-ink-700"><?= $order_date ?></td>
                                                    <td class="px-4 py-3 text-ink-700"><?= $countpro ?></td>
                                                    <td class="px-4 py-3 text-ink-700"><?= $stt ?></td>
                                                    <td class="px-4 py-3 font-medium text-ink-900"><?= number_format($total_amount) ?>₫</td>
                                                    <td class="px-4 py-3">
                                                        <?php if ((int) $status === 0) : ?>
                                                            <form action="index.php?act=cancelorder" method="post"
                                                                data-confirm="Bạn chắc chắn muốn hủy đơn hàng UTP-<?= $id_bill ?>?">
                                                                <input type="hidden" name="id_bill" value="<?= $id_bill ?>">
                                                                <button type="submit"
                                                                    class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-brand-500">
                                                                    <i class="fa-solid fa-xmark" aria-hidden="true"></i> Hủy đơn
                                                                </button>
                                                            </form>
                                                        <?php else : ?>
                                                            <span class="text-xs text-ink-300">—</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Address -->
                        <div id="account-address" data-tab-panel role="tabpanel" aria-labelledby="account-address-tab"
                            class="account-tab-panel hidden rounded-2xl border border-ink-200 bg-ink-800/70 backdrop-blur-xl shadow-sm p-6">
                            <p class="text-sm text-ink-500">Địa chỉ của bạn được sử dụng để đặt và nhận hàng.</p>
                            <div class="mt-4 grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <div>
                                    <h3 class="font-heading font-semibold text-ink-900">Địa chỉ thanh toán</h3>
                                    <address class="mt-2 not-italic text-sm text-ink-700"><?= $_SESSION['user']['address'] ?></address>
                                </div>
                                <div>
                                    <h3 class="font-heading font-semibold text-ink-900">Địa chỉ nhận hàng</h3>
                                    <address class="mt-2 not-italic text-sm text-ink-700"><?= $_SESSION['user']['address'] ?></address>
                                </div>
                            </div>
                        </div>

                        <!-- Account details -->
                        <div id="account-details" data-tab-panel role="tabpanel" aria-labelledby="account-details-tab"
                            class="account-tab-panel hidden rounded-2xl border border-ink-200 bg-ink-800/70 backdrop-blur-xl shadow-sm p-6">
                            <form action="index.php?act=myaccount" method="post" enctype="multipart/form-data" data-validate novalidate>
                            <div class="mb-6 text-center">
                                <img src="uploads/<?= $_SESSION['user']['img_user'] ?>" alt="Avatar người dùng"
                                    class="mx-auto h-24 w-24 rounded-full border border-ink-200 object-cover">
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label for="account-details-avatar" class="block text-sm font-medium text-ink-700 mb-1.5">Ảnh đại diện</label>
                                    <input type="file" name="img_user" id="account-details-avatar"
                                        class="block w-full text-sm text-ink-500 file:mr-4 file:rounded-lg file:border-0 file:bg-brand-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-brand-700 hover:file:bg-brand-100">
                                </div>

                                <div>
                                    <label for="account-details-username" class="block text-sm font-medium text-ink-700 mb-1.5">Tên đăng nhập</label>
                                    <input type="text" name="user_name" id="account-details-username" value="<?= $_SESSION['user']['user_name'] ?>"
                                        placeholder="Nhập họ tên của bạn" disabled
                                        class="block w-full rounded-lg border border-ink-200 bg-ink-100 px-3.5 py-2.5 text-sm text-ink-500">
                                </div>
                                <input type="hidden" name="password" value="<?= $_SESSION['user']['password'] ?>" disabled>

                                <div>
                                    <label for="account-details-fullname" class="block text-sm font-medium text-ink-700 mb-1.5">Họ và tên</label>
                                    <input type="text" name="full_name" id="account-details-fullname" value="<?= $_SESSION['user']['full_name'] ?>"
                                        placeholder="Nhập họ tên của bạn"
                                        data-rules="required|min:2|max:100"
                                        class="block w-full rounded-lg border border-ink-200 bg-ink-800/70 backdrop-blur-xl px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                </div>

                                <div>
                                    <label for="account-details-sex" class="block text-sm font-medium text-ink-700 mb-1.5">Giới tính</label>
                                    <select name="sex" id="account-details-sex" required
                                        class="block w-full rounded-lg border border-ink-200 bg-ink-800/70 backdrop-blur-xl px-3.5 py-2.5 text-sm text-ink-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                        <?php $sexarr = array('0' => 'Nam', '1' => 'Nữ'); ?>
                                        <?php foreach ($sexarr as $key => $value) { ?>
                                            <option value="<?php echo $key; ?>" <?php echo $key ==  $_SESSION['user']['sex'] ? ' selected' : ''; ?>><?php echo $value; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div>
                                    <label for="account-details-email" class="block text-sm font-medium text-ink-700 mb-1.5">Email</label>
                                    <input type="email" name="email_user" id="account-details-email" value="<?= $_SESSION['user']['email_user'] ?>"
                                        placeholder="Nhập địa chỉ email của bạn"
                                        data-rules="required|email"
                                        class="block w-full rounded-lg border border-ink-200 bg-ink-800/70 backdrop-blur-xl px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                </div>

                                <div>
                                    <label for="account-details-province" class="block text-sm font-medium text-ink-700 mb-1.5">Tỉnh/Thành phố</label>
                                    <select name="province" id="account-details-province" data-address-province
                                        data-existing-address="<?= htmlspecialchars($_SESSION['user']['address'] ?? '') ?>"
                                        data-rules="required" data-msg-required="Vui lòng chọn tỉnh/thành phố"
                                        class="block w-full rounded-lg border border-ink-200 bg-ink-800/70 backdrop-blur-xl px-3.5 py-2.5 text-sm text-ink-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                        <option value="">Đang tải danh sách...</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="account-details-ward" class="block text-sm font-medium text-ink-700 mb-1.5">Xã/Phường</label>
                                    <select name="ward" id="account-details-ward" data-address-ward
                                        data-rules="required" data-msg-required="Vui lòng chọn xã/phường"
                                        class="block w-full rounded-lg border border-ink-200 bg-ink-800/70 backdrop-blur-xl px-3.5 py-2.5 text-sm text-ink-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500" disabled>
                                        <option value="">Chọn tỉnh/thành phố trước</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="account-details-address" class="block text-sm font-medium text-ink-700 mb-1.5">Địa chỉ chi tiết</label>
                                    <input type="text" name="address_detail" id="account-details-address" data-address-detail
                                        placeholder="Số nhà, tên đường..."
                                        data-rules="required|min:3|max:255"
                                        class="block w-full rounded-lg border border-ink-200 bg-ink-800/70 backdrop-blur-xl px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                </div>

                                <div>
                                    <label for="account-details-phone" class="block text-sm font-medium text-ink-700 mb-1.5">Số điện thoại</label>
                                    <input type="text" name="phone_user" id="account-details-phone" value="<?= $_SESSION['user']['phone_user'] ?>"
                                        placeholder="Nhập số điện thoại nhận hàng của bạn"
                                        data-rules="required|phone"
                                        class="block w-full rounded-lg border border-ink-200 bg-ink-800/70 backdrop-blur-xl px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                </div>

                                <div class="pt-2">
                                    <input type="hidden" name="id_user" value="<?= $_SESSION['user']['id_user'] ?>">
                                    <button type="submit" name="btn_change" value="Lưu thay đổi"
                                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                        Lưu thay đổi
                                    </button>
                                </div>
                            </div>
                            </form>
                        </div>

                        <!-- Password -->
                        <div id="account-password" data-tab-panel role="tabpanel" aria-labelledby="account-password-tab"
                            class="account-tab-panel hidden rounded-2xl border border-ink-200 bg-ink-800/70 backdrop-blur-xl shadow-sm p-6">
                            <form action="index.php?act=myaccount" method="post" data-validate novalidate>
                            <div class="space-y-4">
                                <div>
                                    <label for="account-password-username" class="block text-sm font-medium text-ink-700 mb-1.5">Tên đăng nhập</label>
                                    <input type="text" name="user_name" id="account-password-username" value="<?= $_SESSION['user']['user_name'] ?>"
                                        placeholder="Nhập họ tên của bạn" disabled
                                        class="block w-full rounded-lg border border-ink-200 bg-ink-100 px-3.5 py-2.5 text-sm text-ink-500">
                                </div>
                                <div>
                                    <label for="account-password-newpass" class="block text-sm font-medium text-ink-700 mb-1.5">Mật khẩu mới</label>
                                    <input type="password" id="account-password-newpass" name="newpass" placeholder="Nhập mật khẩu mới"
                                        data-rules="required|min:6"
                                        class="block w-full rounded-lg border border-ink-200 bg-ink-800/70 backdrop-blur-xl px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                </div>
                                <div>
                                    <label for="account-password-confpass" class="block text-sm font-medium text-ink-700 mb-1.5">Xác nhận mật khẩu mới</label>
                                    <input type="password" id="account-password-confpass" name="repass" placeholder="Nhập lại mật khẩu mới"
                                        data-rules="required|match:newpass" data-msg-match="Mật khẩu xác nhận không khớp"
                                        class="block w-full rounded-lg border border-ink-200 bg-ink-800/70 backdrop-blur-xl px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                </div>

                                <div class="pt-2">
                                    <input type="hidden" name="id_user" value="<?= $_SESSION['user']['id_user'] ?>">
                                    <button type="submit" name="btn_pass" value="Lưu thay đổi"
                                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                        Lưu thay đổi
                                    </button>
                                </div>
                            </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
</main>

<script>
    (function () {
        var triggers = document.querySelectorAll('[data-tab-target]');
        var panels = document.querySelectorAll('[data-tab-panel]');

        function activateTab(targetId) {
            var trigger = document.querySelector('[data-tab-target="' + targetId + '"]');
            if (!trigger) return;

            panels.forEach(function (panel) {
                panel.classList.toggle('hidden', panel.id !== targetId);
            });

            triggers.forEach(function (t) {
                var isActive = t === trigger;
                t.setAttribute('aria-selected', isActive ? 'true' : 'false');
                t.classList.toggle('text-brand-600', isActive);
                t.classList.toggle('bg-brand-50', isActive);
                t.classList.toggle('text-ink-700', !isActive);
            });
        }

        triggers.forEach(function (trigger) {
            trigger.addEventListener('click', function (e) {
                e.preventDefault();
                activateTab(trigger.getAttribute('data-tab-target'));
            });
        });

        // Reopen whichever tab the URL hash points to (e.g. after a
        // redirect-driven action like cancelling an order, which lands back
        // here as `?act=myaccount#account-orders`) — otherwise a fresh page
        // load always falls back to the first ("Bảng điều khiển") tab, even
        // when the user was just doing something on a different tab.
        if (window.location.hash) {
            activateTab(window.location.hash.substring(1));
        }
    })();
</script>
<!-- JB's Page Content Area End Here -->
