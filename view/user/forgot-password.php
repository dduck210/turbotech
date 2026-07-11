<?php

/** @var string|null $error Validation/lookup error message, if any. */
?>
<!-- Breadcrumb -->
<div class="border-b border-ink-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-3">
        <nav aria-label="Breadcrumb" class="text-sm text-ink-500">
            <ol class="flex items-center gap-2">
                <li><a href="index.php" class="hover:text-brand-600">Trang chủ</a></li>
                <li aria-hidden="true">/</li>
                <li class="font-medium text-ink-900" aria-current="page">Quên mật khẩu</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Form quên mật khẩu: khôi phục qua email hoặc số điện thoại, mã xác nhận
     luôn được gửi về email của tài khoản (không có cổng gửi SMS). -->
<div class="bg-ink-50">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12 md:py-16">
        <div class="mx-auto max-w-md rounded-2xl border border-ink-200 bg-white shadow-sm p-6 sm:p-8">
            <form action="index.php?act=mk" method="post" data-validate novalidate>
                <h1 class="mb-2 text-center font-heading text-2xl font-bold text-ink-900">Quên mật khẩu</h1>
                <p class="mb-6 text-center text-sm text-ink-500">Nhập email hoặc số điện thoại đã đăng ký, chúng tôi sẽ gửi mã xác nhận đến email của tài khoản.</p>

                <?php if ($error) : ?>
                    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3.5 text-sm text-red-700">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <div class="mb-6">
                    <label for="identifier" class="block text-sm font-medium text-ink-700 mb-1.5">Email hoặc số điện thoại</label>
                    <input type="text" id="identifier" name="identifier" placeholder="Nhập email hoặc số điện thoại"
                        data-rules="required"
                        class="block w-full rounded-lg border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                </div>

                <button type="submit" name="btn_forgot" value="Gửi mã xác nhận"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    Gửi mã xác nhận
                </button>

                <p class="mt-6 text-center text-sm text-ink-500">
                    <a href="index.php?act=login" class="font-medium text-brand-600 hover:text-brand-700">Quay lại đăng nhập</a>
                </p>
            </form>
        </div>
    </div>
</div>