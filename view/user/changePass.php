<?php

/** @var array $error Validation errors keyed by field, e.g. $error['fail']. */
?>
<!-- Breadcrumb -->
<div class="border-b border-ink-200">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-3">
        <nav aria-label="Breadcrumb" class="text-sm text-ink-500">
            <ol class="flex items-center gap-2">
                <li><a href="index.php" class="hover:text-brand-600">Trang chủ</a></li>
                <li aria-hidden="true">/</li>
                <li class="font-medium text-ink-900" aria-current="page">Đổi mật khẩu</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Form đổi mật khẩu: split panel (brand quote + form), was: single centered glass card -->
<div class="bg-ink-100">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-12 md:py-20">
        <div class="mx-auto grid max-w-3xl grid-cols-1 overflow-hidden rounded-lg border border-ink-300 lg:grid-cols-2">
            <div class="hidden flex-col justify-center bg-ink-900 p-10 text-ink-100 lg:flex">
                <i class="fa-solid fa-lock text-2xl text-brand-400" aria-hidden="true"></i>
                <p class="mt-4 font-heading text-xl font-medium leading-snug text-ink-50">
                    Chọn một mật khẩu mới thật an toàn cho tài khoản của bạn.
                </p>
                <span class="mt-6 block h-px w-12 bg-brand-500"></span>
            </div>

            <div class="bg-ink-50 p-6 sm:p-10">
                <form action="index.php?act=changePass" method="post" data-validate novalidate>
<?= \Codemoi\Core\Csrf::field() ?>
                    <h1 class="mb-6 font-heading text-2xl font-semibold text-ink-900">Đổi mật khẩu</h1>

                    <div class="mb-4">
                        <label for="changepass-newpass" class="block text-sm font-medium text-ink-700 mb-1.5">Mật khẩu mới</label>
                        <input type="password" id="changepass-newpass" name="newpass" placeholder="Nhập mật khẩu mới"
                            data-rules="required|min:6"
                            class="block w-full rounded-md border border-ink-300 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-500 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    </div>

                    <div class="mb-6">
                        <label for="changepass-repass" class="block text-sm font-medium text-ink-700 mb-1.5">Xác nhận mật khẩu mới</label>
                        <input type="password" id="changepass-repass" name="repass" placeholder="Nhập lại mật khẩu mới"
                            data-rules="required|match:newpass" data-msg-match="Mật khẩu xác nhận không khớp"
                            class="block w-full rounded-md border border-ink-300 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-500 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                        <?php if (isset($error['fail'])) : ?>
                            <p class="mt-1.5 text-sm text-red-600"><?= e($error['fail']) ?></p>
                        <?php endif; ?>
                    </div>

                    <button type="submit" name="btn_changePass" value="Đổi mật khẩu"
                        class="btn-boutique inline-flex w-full items-center justify-center gap-2 rounded-md px-5 py-2.5 text-sm font-semibold disabled:opacity-50 disabled:cursor-not-allowed">
                        Đổi mật khẩu
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
