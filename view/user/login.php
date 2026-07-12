<!-- Breadcrumb -->
<div class="border-b border-ink-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-3">
        <nav aria-label="Breadcrumb" class="text-sm text-ink-500">
            <ol class="flex items-center gap-2">
                <li><a href="index.php" class="hover:text-brand-600">Trang chủ</a></li>
                <li aria-hidden="true">/</li>
                <li class="font-medium text-ink-900" aria-current="page">Đăng nhập</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Form đăng nhập -->
<div class="bg-ink-50">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12 md:py-16">
        <?php if (isset($noti_success) && $noti_success != "") : ?>
            <div class="mx-auto mb-6 max-w-md rounded-lg border border-green-200 bg-green-50 p-3 text-sm text-green-700">
                <?php echo e($noti_success); ?>
            </div>
        <?php endif; ?>

        <div class="mx-auto max-w-md rounded-2xl border border-ink-200 bg-white shadow-sm p-6 sm:p-8">
            <form action="index.php?act=login" method="post" data-validate novalidate>
<?= \Codemoi\Core\Csrf::field() ?>
                <h1 class="mb-6 text-center font-heading text-2xl font-bold text-ink-900">Đăng nhập tài khoản</h1>

                <div class="mb-4">
                    <label for="login-user-name" class="block text-sm font-medium text-ink-700 mb-1.5">Tài khoản</label>
                    <input type="text" id="login-user-name" name="user_name" placeholder="Nhập tên tài khoản hoặc địa chỉ email của bạn"
                        data-rules="required"
                        class="block w-full rounded-lg border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                </div>

                <div class="mb-4">
                    <label for="login-password" class="block text-sm font-medium text-ink-700 mb-1.5">Mật khẩu</label>
                    <div class="relative">
                        <input type="password" id="login-password" name="password" placeholder="Nhập mật khẩu của bạn"
                            data-rules="required"
                            class="block w-full rounded-lg border border-ink-200 bg-white px-3.5 py-2.5 pr-11 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                        <button type="button" id="login-password-toggle" aria-label="Hiện/ẩn mật khẩu"
                            class="absolute inset-y-0 right-0 flex h-11 w-11 items-center justify-center text-ink-500 hover:text-ink-900 focus:outline-none focus:ring-2 focus:ring-brand-500 rounded-lg">
                            <i class="fa-regular fa-eye" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="remember_me"
                            class="h-4 w-4 rounded border-ink-200 text-brand-600 focus:ring-2 focus:ring-brand-500">
                        <label for="remember_me" class="text-sm text-ink-700">Ghi nhớ tài khoản</label>
                    </div>
                    <a href="index.php?act=mk" class="text-sm font-medium text-brand-600 hover:text-brand-700">Quên mật khẩu?</a>
                </div>

                <button type="submit" name="btn_login" value="Đăng nhập"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    Đăng nhập
                </button>

                <p class="mt-6 text-center text-sm text-ink-500">
                    Chưa có tài khoản?
                    <a href="index.php?act=register" class="font-medium text-brand-600 hover:text-brand-700">Đăng ký tài khoản mới</a>
                </p>
            </form>
        </div>
    </div>
</div>

<script>
    (function() {
        var toggle = document.getElementById('login-password-toggle');
        var input = document.getElementById('login-password');
        if (!toggle || !input) return;
        toggle.addEventListener('click', function() {
            var isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            toggle.innerHTML = isHidden ?
                '<i class="fa-regular fa-eye-slash" aria-hidden="true"></i>' :
                '<i class="fa-regular fa-eye" aria-hidden="true"></i>';
        });
    })();
</script>