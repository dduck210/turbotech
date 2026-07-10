<!-- Breadcrumb -->
<div class="border-b border-ink-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-3">
        <nav aria-label="Breadcrumb" class="text-sm text-ink-500">
            <ol class="flex items-center gap-2">
                <li><a href="index.php" class="hover:text-brand-600">Trang chủ</a></li>
                <li aria-hidden="true">/</li>
                <li class="font-medium text-ink-900" aria-current="page">Đăng ký</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Form đăng ký -->
<div class="bg-ink-50">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12 md:py-16">
        <div class="mx-auto max-w-md rounded-2xl border border-ink-200 bg-white shadow-sm p-6 sm:p-8">
            <form id="registrationForm" action="index.php?act=register" method="post" data-validate novalidate>
                <h1 class="mb-6 text-center font-heading text-2xl font-bold text-ink-900">Đăng ký tài khoản</h1>

                <div class="mb-4">
                    <label for="user_name" class="block text-sm font-medium text-ink-700 mb-1.5">Tên đăng nhập</label>
                    <input type="text" id="user_name" name="user_name" placeholder="Tạo tên đăng nhập của bạn"
                        data-rules="required|min:3|max:50"
                        class="block w-full rounded-lg border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                </div>

                <div class="mb-4">
                    <label for="full_name" class="block text-sm font-medium text-ink-700 mb-1.5">Họ tên</label>
                    <input type="text" id="full_name" name="full_name" placeholder="Nhập họ tên của bạn"
                        data-rules="required|min:2|max:100"
                        class="block w-full rounded-lg border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                </div>

                <div class="mb-4">
                    <label for="sex" class="block text-sm font-medium text-ink-700 mb-1.5">Giới tính</label>
                    <select id="sex" name="sex"
                        class="block w-full rounded-lg border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                        <option value="0">Nam</option>
                        <option value="1">Nữ</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="email_user" class="block text-sm font-medium text-ink-700 mb-1.5">Email</label>
                    <input type="email" id="email_user" name="email_user" placeholder="Nhập địa chỉ email của bạn"
                        data-rules="required|email"
                        class="block w-full rounded-lg border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                </div>

                <div class="mb-4">
                    <label for="province" class="block text-sm font-medium text-ink-700 mb-1.5">Tỉnh/Thành phố</label>
                    <select id="province" name="province" data-address-province
                        data-rules="required" data-msg-required="Vui lòng chọn tỉnh/thành phố"
                        class="block w-full rounded-lg border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                        <option value="">Đang tải danh sách...</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="ward" class="block text-sm font-medium text-ink-700 mb-1.5">Xã/Phường</label>
                    <select id="ward" name="ward" data-address-ward
                        data-rules="required" data-msg-required="Vui lòng chọn xã/phường"
                        class="block w-full rounded-lg border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500" disabled>
                        <option value="">Chọn tỉnh/thành phố trước</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="address_detail" class="block text-sm font-medium text-ink-700 mb-1.5">Địa chỉ chi tiết</label>
                    <input type="text" id="address_detail" name="address_detail" placeholder="Số nhà, tên đường..."
                        data-rules="required|min:3|max:255"
                        class="block w-full rounded-lg border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                </div>

                <div class="mb-4">
                    <label for="phone_user" class="block text-sm font-medium text-ink-700 mb-1.5">Số điện thoại</label>
                    <input type="tel" id="phone_user" name="phone_user" placeholder="Nhập số điện thoại nhận hàng"
                        data-rules="required|phone"
                        class="block w-full rounded-lg border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-ink-700 mb-1.5">Mật khẩu</label>
                    <input type="password" id="password" name="password" placeholder="Tạo mật khẩu của bạn"
                        data-rules="required|min:6"
                        class="block w-full rounded-lg border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                </div>

                <button type="submit" name="btn_register" value="Đăng ký"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    Đăng ký
                </button>

                <p class="mt-6 text-center text-sm text-ink-500">
                    Đã có tài khoản?
                    <a href="index.php?act=login" class="font-medium text-brand-600 hover:text-brand-700">Đăng nhập</a>
                </p>
            </form>
        </div>
    </div>
</div>
