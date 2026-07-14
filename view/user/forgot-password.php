<?php

/** @var string|null $error Validation/lookup error message, if any. */
?>
<!-- Breadcrumb -->
<div class="border-b border-ink-200">
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
     luôn được gửi về email của tài khoản (không có cổng gửi SMS).
     Split panel (brand quote + form), was: single centered glass card. -->
<div class="bg-ink-100">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-12 md:py-20">
        <div class="mx-auto grid max-w-3xl grid-cols-1 overflow-hidden rounded-lg border border-ink-300 lg:grid-cols-2">
            <div class="hidden flex-col justify-center bg-ink-900 p-10 text-ink-100 lg:flex">
                <i class="fa-solid fa-key text-2xl text-brand-400" aria-hidden="true"></i>
                <p class="mt-4 font-heading text-xl font-medium leading-snug text-ink-50">
                    Khôi phục quyền truy cập tài khoản của bạn chỉ trong vài bước.
                </p>
                <span class="mt-6 block h-px w-12 bg-brand-500"></span>
            </div>

            <div class="bg-ink-50 p-6 sm:p-10">
                <form action="index.php?act=mk" method="post" data-validate novalidate>
<?= \Codemoi\Core\Csrf::field() ?>
                    <h1 class="mb-2 font-heading text-2xl font-semibold text-ink-900">Quên mật khẩu</h1>
                    <p class="mb-6 text-sm text-ink-500">Nhập email hoặc số điện thoại đã đăng ký, chúng tôi sẽ gửi mã xác nhận đến email của tài khoản.</p>

                    <?php if ($error) : ?>
                        <div class="mb-4 rounded-md border border-red-500/30 bg-red-500/10 p-3.5 text-sm text-red-700">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <div class="mb-6">
                        <label for="identifier" class="block text-sm font-medium text-ink-700 mb-1.5">Email hoặc số điện thoại</label>
                        <input type="text" id="identifier" name="identifier" placeholder="Nhập email hoặc số điện thoại"
                            data-rules="required"
                            class="block w-full rounded-md border border-ink-300 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-500 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    </div>

                    <button type="submit" name="btn_forgot" value="Gửi mã xác nhận"
                        class="btn-boutique inline-flex w-full items-center justify-center gap-2 rounded-md px-5 py-2.5 text-sm font-semibold disabled:opacity-50 disabled:cursor-not-allowed">
                        Gửi mã xác nhận
                    </button>

                    <p class="mt-6 text-center text-sm text-ink-500">
                        <a href="index.php?act=login" class="font-medium text-brand-600 hover:text-brand-700">Quay lại đăng nhập</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
