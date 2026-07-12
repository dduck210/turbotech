<?php

/** @var array $error Validation errors keyed by field, e.g. $error['fail']. */
?>
<!-- Breadcrumb -->
<div class="border-b border-ink-200 bg-white">
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

<!-- Form đổi mật khẩu -->
<div class="bg-ink-50">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12 md:py-16">
        <div class="mx-auto max-w-md rounded-2xl border border-ink-200 bg-white shadow-sm p-6 sm:p-8">
            <form action="index.php?act=changePass" method="post" data-validate novalidate>
<?= \Codemoi\Core\Csrf::field() ?>
                <h1 class="mb-6 text-center font-heading text-2xl font-bold text-ink-900">Đổi mật khẩu</h1>

                <div class="mb-4">
                    <label for="changepass-newpass" class="block text-sm font-medium text-ink-700 mb-1.5">Mật khẩu mới</label>
                    <input type="password" id="changepass-newpass" name="newpass" placeholder="Nhập mật khẩu mới"
                        data-rules="required|min:6"
                        class="block w-full rounded-lg border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                </div>

                <div class="mb-6">
                    <label for="changepass-repass" class="block text-sm font-medium text-ink-700 mb-1.5">Xác nhận mật khẩu mới</label>
                    <input type="password" id="changepass-repass" name="repass" placeholder="Nhập lại mật khẩu mới"
                        data-rules="required|match:newpass" data-msg-match="Mật khẩu xác nhận không khớp"
                        class="block w-full rounded-lg border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    <?php if (isset($error['fail'])) : ?>
                        <p class="mt-1.5 text-sm text-red-600"><?php echo e($error['fail']); ?></p>
                    <?php endif; ?>
                </div>

                <button type="submit" name="btn_changePass" value="Đổi mật khẩu"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    Đổi mật khẩu
                </button>
            </form>
        </div>
    </div>
</div>