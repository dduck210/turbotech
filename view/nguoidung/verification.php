<!-- Breadcrumb -->
<div class="border-b border-ink-200 bg-white">
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

<!-- Form nhập mã xác minh -->
<div class="bg-ink-50">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12 md:py-16">
        <div class="mx-auto max-w-md rounded-2xl border border-ink-200 bg-white shadow-sm p-6 sm:p-8">
            <form action="index.php?act=verification" method="post">
                <?php
                if (isset($_POST['btn_verification'])) {
                    $error = array();
                    if ($_POST['ma'] != $_SESSION['code']) {
                        $error['fali'] = 'Mã xác nhận không hợp lệ !';
                    } else {
                        header('Location: index.php?act=changePass');
                    }
                }
                ?>
                <h1 class="mb-2 text-center font-heading text-2xl font-bold text-ink-900">Nhập mã xác nhận</h1>
                <p class="mb-6 text-center text-sm font-medium text-red-600">Hãy nhập mã xác nhận mà chúng tôi đã gửi cho bạn về Email</p>

                <div class="mb-6">
                    <label for="verification-ma" class="block text-sm font-medium text-ink-700 mb-1.5">Mã xác nhận</label>
                    <input type="text" id="verification-ma" name="ma" placeholder="Nhập mã xác nhận"
                        class="block w-full rounded-lg border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    <?php if (isset($error['fali'])) : ?>
                        <p class="mt-1.5 text-sm text-red-600"><?php echo $error['fali']; ?></p>
                    <?php endif; ?>
                </div>

                <button type="submit" name="btn_verification" value="Xác nhận"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    Xác nhận
                </button>
            </form>
        </div>
    </div>
</div>
