<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Turbotech Admin - Đăng nhập</title>
    <link rel="shortcut icon" type="image/x-icon" href="view/assets/img/logoadmin.png" />
    
    <!-- Modern Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Tailwind CSS: precompiled via standalone CLI, same stylesheet as header.php
         (src/css/admin-tailwind-input.css -> admin/view/assets/css/tailwind.css) —
         replaces the @tailwindcss/browser CDN runtime compiler. -->
    <link rel="stylesheet" href="view/assets/css/tailwind.css" />
</head>
<body class="bg-slate-900 text-slate-800 antialiased flex items-center justify-center min-h-screen relative overflow-hidden">
    <!-- Decorative Background Shapes -->
    <div class="bg-shape bg-brand-600 w-[500px] h-[500px] top-[-200px] left-[-200px]"></div>
    <div class="bg-shape bg-purple-600 w-[400px] h-[400px] bottom-[-100px] right-[-100px]"></div>

    <div class="container mx-auto px-4 w-full max-w-5xl relative z-10">
        <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl overflow-hidden border border-white/20">
            <div class="flex flex-col lg:flex-row">
                <!-- Image Section -->
                <div class="hidden lg:flex lg:w-1/2 bg-slate-50 items-center justify-center p-12 relative overflow-hidden group">
                    <div class="absolute inset-0 bg-linear-to-br from-brand-50 to-purple-50 opacity-50"></div>
                    <img src="uploads/anhad.png" alt="logo Turbotech" class="w-full max-w-sm relative z-10 drop-shadow-2xl group-hover:scale-105 transition-transform duration-700">
                </div>
                
                <!-- Form Section -->
                <div class="w-full lg:w-1/2 p-8 md:p-12 lg:p-16 flex flex-col justify-center">
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-bold text-slate-800 mb-2 font-heading">Trang quản trị</h1>
                        <p class="text-slate-500">Chào mừng trở lại Turbotech!</p>
                    </div>

                    <form class="space-y-6" action="index.php?act=login" method="post">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Tài khoản</label>
                            <input type="text" name="user_name" placeholder="Nhập tài khoản" class="w-full rounded-xl border border-slate-200 px-5 py-3 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all bg-slate-50 focus:bg-white text-slate-800" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Mật khẩu</label>
                            <input type="password" name="password" placeholder="Nhập mật khẩu" class="w-full rounded-xl border border-slate-200 px-5 py-3 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all bg-slate-50 focus:bg-white text-slate-800" required>
                        </div>

                        <input type="submit" name="btn_login" value="Đăng nhập" class="w-full bg-slate-900 hover:bg-brand-600 text-white font-semibold rounded-xl px-4 py-3.5 mt-4 transition-all active:scale-[0.98] cursor-pointer shadow-lg shadow-slate-900/20 hover:shadow-brand-500/30">
                    </form>

                    <div class="mt-6 text-center text-green-500 font-medium text-sm">
                        <?php
                        if (isset($noti_success) && $noti_success != "") {
                            echo $noti_success;
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center text-slate-400 text-sm mt-8 font-medium tracking-wide">
            &copy; 2026 Turbotech Admin. Premium Access.
        </div>
    </div>
</body>
</html>