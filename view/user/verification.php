<!-- Breadcrumb -->
<div class="border-b border-ink-200">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-3">
        <nav aria-label="Breadcrumb" class="text-sm text-ink-500">
            <ol class="flex items-center gap-2">
                <li><a href="index.php" class="hover:text-brand-600">Trang chủ</a></li>
                <li aria-hidden="true">/</li>
                <li class="font-medium text-ink-900" aria-current="page">Nhập mã xác nhận</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Form nhập mã xác minh: split panel (brand quote + form), was: single centered glass card -->
<div class="bg-ink-100">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-12 md:py-20">
        <div class="mx-auto grid max-w-3xl grid-cols-1 overflow-hidden rounded-lg border border-ink-300 lg:grid-cols-2">
            <div class="hidden flex-col justify-center bg-ink-900 p-10 text-ink-100 lg:flex">
                <i class="fa-solid fa-envelope-open-text text-2xl text-brand-400" aria-hidden="true"></i>
                <p class="mt-4 font-heading text-xl font-medium leading-snug text-ink-50">
                    Kiểm tra hộp thư email của bạn để lấy mã xác nhận.
                </p>
                <span class="mt-6 block h-px w-12 bg-brand-500"></span>
            </div>

            <div class="bg-ink-50 p-6 sm:p-10">
                <form action="index.php?act=verification" method="post" data-validate novalidate>
<?= \Codemoi\Core\Csrf::field() ?>
                    <?php
                    if (isset($_POST['btn_verification'])) {
                        $error = array();
                        $attempts = $_SESSION['code_attempts'] ?? 0;
                        if (!isset($_SESSION['code'], $_SESSION['code_expires'])) {
                            $error['fali'] = 'Phiên xác nhận đã hết hạn, vui lòng yêu cầu mã mới !';
                        } elseif ($attempts >= 5) {
                            unset($_SESSION['code'], $_SESSION['code_expires'], $_SESSION['code_attempts']);
                            $error['fali'] = 'Bạn đã nhập sai quá nhiều lần, vui lòng yêu cầu mã mới !';
                        } elseif (time() > $_SESSION['code_expires']) {
                            unset($_SESSION['code'], $_SESSION['code_expires'], $_SESSION['code_attempts']);
                            $error['fali'] = 'Mã xác nhận đã hết hạn, vui lòng yêu cầu mã mới !';
                        } elseif (!hash_equals((string) $_SESSION['code'], (string) ($_POST['ma'] ?? ''))) {
                            $_SESSION['code_attempts'] = $attempts + 1;
                            $error['fali'] = 'Mã xác nhận không hợp lệ !';
                        } else {
                            unset($_SESSION['code'], $_SESSION['code_expires'], $_SESSION['code_attempts']);
                            $_SESSION['otp_verified'] = true;
                            header('Location: index.php?act=changePass');
                            exit;
                        }
                    }
                    ?>
                    <h1 class="mb-2 font-heading text-2xl font-semibold text-ink-900">Nhập mã xác nhận</h1>
                    <p class="mb-6 text-sm font-medium text-red-600">Hãy nhập mã xác nhận mà chúng tôi đã gửi cho bạn về Email</p>

                    <div class="mb-6">
                        <label for="verification-ma" class="block text-sm font-medium text-ink-700 mb-1.5">Mã xác nhận</label>
                        <input type="text" id="verification-ma" name="ma" placeholder="Nhập mã xác nhận"
                            data-rules="required"
                            class="block w-full rounded-md border border-ink-300 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-500 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                        <?php if (isset($error['fali'])) : ?>
                            <p class="mt-1.5 text-sm text-red-600"><?= e($error['fali']) ?></p>
                        <?php endif; ?>
                    </div>

                    <button type="submit" name="btn_verification" value="Xác nhận"
                        class="btn-boutique inline-flex w-full items-center justify-center gap-2 rounded-md px-5 py-2.5 text-sm font-semibold disabled:opacity-50 disabled:cursor-not-allowed">
                        Xác nhận
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
