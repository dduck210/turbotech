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

<!-- Chọn cách thức lấy lại mật khẩu -->
<div class="bg-ink-50">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12 md:py-16">
        <div class="mx-auto max-w-md rounded-2xl border border-ink-200 bg-white shadow-sm p-6 sm:p-8">
            <form action="index.php?act=mk" method="post">
                <h1 class="mb-6 text-center font-heading text-2xl font-bold text-ink-900">Cách thức lấy lại mật khẩu</h1>

                <div class="flex flex-col gap-3">
                    <a href="index.php?act=forgotPass"
                        class="flex items-center gap-3 rounded-lg border border-ink-200 bg-white px-4 py-3.5 text-sm font-semibold text-ink-900 hover:bg-ink-50 hover:border-brand-500 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500">
                        <i class="fa-regular fa-envelope text-brand-600" aria-hidden="true"></i>
                        Gửi mã qua email
                    </a>
                    <a href="index.php?act=usermk"
                        class="flex items-center gap-3 rounded-lg border border-ink-200 bg-white px-4 py-3.5 text-sm font-semibold text-ink-900 hover:bg-ink-50 hover:border-brand-500 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500">
                        <i class="fa-regular fa-user text-brand-600" aria-hidden="true"></i>
                        Thông qua tên đăng nhập và email
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
