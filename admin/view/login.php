<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Turbotech Admin - Đăng nhập</title>
    <link rel="icon" type="image/svg+xml" href="../assets/images/menu/logo/favicon.svg" />

    <!-- Modern Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Manrope:wght@600;700;800&family=Playfair+Display:wght@500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Tailwind CSS: precompiled via standalone CLI, same stylesheet as header.php
         (src/css/admin-tailwind-input.css -> public/assets/css/admin-tailwind.css) —
         replaces the @tailwindcss/browser CDN runtime compiler. -->
    <link rel="stylesheet" href="../assets/css/admin-tailwind.css" />
</head>

<body class="bg-ink-100 text-ink-600 antialiased flex items-center justify-center min-h-screen p-4">
    <?php if (!empty($flash_error)): ?>
    <script>document.addEventListener("DOMContentLoaded",()=>Swal.fire({toast:true,position:"top-end",icon:"error",title:<?= json_encode($flash_error) ?>,showConfirmButton:false,timer:4000}));</script>
    <?php endif; ?>

    <div class="container mx-auto w-full max-w-5xl">
        <div class="card-boutique rounded-lg overflow-hidden">
            <div class="flex flex-col lg:flex-row">
                <!-- Brand Panel -->
                <div class="lg:w-2/5 bg-ink-900 text-ink-100 p-10 md:p-12 flex flex-col justify-between">
                    <img src="../assets/images/menu/logo/logo-wordmark-dark.svg" alt="Turbotech"
                        class="h-10 w-auto">
                    <div class="my-10">
                        <span class="block w-10 h-px bg-brand-400 mb-6"></span>
                        <p class="font-heading text-2xl leading-snug text-ink-50">
                            Không gian quản trị dành riêng cho đội ngũ Turbotech.
                        </p>
                    </div>
                    <p class="text-xs text-ink-400 tracking-wide">&copy; 2026 Turbotech. Boutique Commerce.</p>
                </div>

                <!-- Form Section -->
                <div class="w-full lg:w-3/5 p-8 md:p-12 lg:p-16 flex flex-col justify-center">
                    <div class="mb-8">
                        <span class="block w-10 h-px bg-brand-500 mb-4"></span>
                        <h1 class="font-heading text-3xl text-ink-900 mb-2">Trang quản trị</h1>
                        <p class="text-ink-500">Chào mừng trở lại Turbotech!</p>
                    </div>

                    <form class="space-y-5" action="index.php?act=login" method="post" data-validate novalidate>
<?= \Codemoi\Core\Csrf::field() ?>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Tài khoản</label>
                            <input type="text" name="user_name" placeholder="Nhập tài khoản" data-rules="required"
                                class="w-full rounded-md border border-ink-300 px-4 py-3 focus:ring-2 focus:ring-brand-400 focus:border-brand-500 outline-none transition-all bg-ink-50 text-ink-800">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-ink-500 mb-2">Mật khẩu</label>
                            <input type="password" name="password" placeholder="Nhập mật khẩu" data-rules="required"
                                class="w-full rounded-md border border-ink-300 px-4 py-3 focus:ring-2 focus:ring-brand-400 focus:border-brand-500 outline-none transition-all bg-ink-50 text-ink-800">
                        </div>

                        <input type="submit" name="btn_login" value="Đăng nhập"
                            class="btn-boutique w-full font-semibold rounded-md px-4 py-3 mt-2 cursor-pointer">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/js/form-validate.js"></script>
</body>

</html>
