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

<!-- Form tìm lại mật khẩu qua tên đăng nhập + email -->
<div class="bg-ink-50">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12 md:py-16">
        <div class="mx-auto max-w-md rounded-2xl border border-ink-200 bg-white shadow-sm p-6 sm:p-8">
            <form action="index.php?act=usermk" method="post">
                <h1 class="mb-6 text-center font-heading text-2xl font-bold text-ink-900">Quên mật khẩu</h1>

                <div class="mb-4">
                    <label for="laymk2-user-name" class="block text-sm font-medium text-ink-700 mb-1.5">Tài khoản</label>
                    <input type="text" id="laymk2-user-name" name="user_name" placeholder="Nhập tên tài khoản"
                        class="block w-full rounded-lg border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                </div>

                <div class="mb-6">
                    <label for="laymk2-email" class="block text-sm font-medium text-ink-700 mb-1.5">Email</label>
                    <input type="email" id="laymk2-email" name="email" placeholder="Nhập email của bạn"
                        class="block w-full rounded-lg border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                </div>

                <button type="submit" name="mk2" value="Tìm mật khẩu"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    Tìm mật khẩu
                </button>

                <div class="mt-6 flex items-center justify-between text-sm">
                    <a href="index.php?act=login" class="font-medium text-brand-600 hover:text-brand-700">Đăng nhập</a>
                    <a href="index.php?act=register" class="font-medium text-brand-600 hover:text-brand-700">Đăng ký tài khoản mới</a>
                </div>

                <?php if (isset($thongbao) && ($thongbao != "")) : ?>
                    <div class="mt-6 rounded-lg border border-ink-200 bg-ink-50 p-3 text-sm">
                        <?php echo $thongbao; ?>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>
