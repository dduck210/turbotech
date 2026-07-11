<footer class="bg-ink-900 text-ink-100">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 md:py-16 lg:px-8">
        <div class="grid grid-cols-1 gap-10 md:grid-cols-3">
            <!-- Brand + quick links -->
            <div>
                <a href="index.php" class="inline-flex items-center gap-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <span class="font-heading text-xl font-bold text-white">TURBOTECH</span>
                </a>
                <nav class="mt-4 flex flex-wrap gap-x-4 gap-y-2 text-sm">
                    <a href="index.php" class="text-ink-300 transition-colors hover:text-white focus:outline-none focus:ring-2 focus:ring-brand-500">Trang chủ</a>
                    <a href="index.php?act=product" class="text-ink-300 transition-colors hover:text-white focus:outline-none focus:ring-2 focus:ring-brand-500">Sản phẩm</a>
                    <a href="index.php?act=introduce" class="text-ink-300 transition-colors hover:text-white focus:outline-none focus:ring-2 focus:ring-brand-500">Giới thiệu</a>
                    <a href="index.php?act=contact" class="text-ink-300 transition-colors hover:text-white focus:outline-none focus:ring-2 focus:ring-brand-500">Liên hệ</a>
                    <a href="index.php?act=question" class="text-ink-300 transition-colors hover:text-white focus:outline-none focus:ring-2 focus:ring-brand-500">Hỏi đáp</a>
                </nav>
                <p class="mt-6 text-sm text-ink-300">Tubotech &copy; 2026</p>
            </div>

            <!-- Contact -->
            <div>
                <h3 class="font-heading text-sm font-semibold uppercase tracking-wide text-white">Liên hệ</h3>
                <ul class="mt-4 space-y-3 text-sm text-ink-300">
                    <li class="flex items-start gap-3">
                        <i class="fa-solid fa-location-dot mt-0.5 w-4 text-brand-500"></i>
                        <span>Số 999 Gia lâm, Hà Nội</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fa-solid fa-phone w-4 text-brand-500"></i>
                        <span>0987651234</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fa-solid fa-envelope w-4 text-brand-500"></i>
                        <a href="#" class="transition-colors hover:text-white focus:outline-none focus:ring-2 focus:ring-brand-500">tubotech@gmail.com</a>
                    </li>
                </ul>
            </div>

            <!-- About -->
            <div>
                <h3 class="font-heading text-sm font-semibold uppercase tracking-wide text-white">Giới thiệu thương hiệu</h3>
                <p class="mt-4 text-sm leading-relaxed text-ink-300">
                    Turbotech ra đời với sứ mệnh mang đến cho khách hàng một không gian mua sắm công nghệ an tâm tuyệt
                    đối. Chúng tôi chuyên cung cấp đa dạng các dòng Laptop, Máy tính đồng bộ, PC Gaming, PC Đồ họa và
                    linh kiện máy tính chính hãng với mức giá cạnh tranh nhất thị trường, đảm bảo uy tín và chất lượng
                    hàng đầu.
                </p>
            </div>
        </div>
    </div>
</footer>

<!-- Custom confirm dialog (replaces the native browser confirm() popup) —
     logic lives in assets/js/confirm-dialog.js; markup stays here so its
     classes are seen by Tailwind's content scan. -->
<div id="confirm-dialog-overlay" class="hidden fixed inset-0 z-50 bg-ink-900/50 p-4">
    <div class="flex h-full w-full items-center justify-center">
        <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl">
            <div class="mb-4 flex items-center gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                    <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
                </div>
                <h3 class="font-heading text-base font-semibold text-ink-900">Xác nhận</h3>
            </div>
            <p id="confirm-dialog-message" class="mb-6 text-sm text-ink-600"></p>
            <div class="flex justify-end gap-3">
                <button type="button" id="confirm-dialog-cancel" class="rounded-lg border border-ink-200 bg-white px-4 py-2 text-sm font-semibold text-ink-900 transition-colors hover:bg-ink-50 focus:outline-none focus:ring-2 focus:ring-brand-500">Hủy</button>
                <button type="button" id="confirm-dialog-ok" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">Xác nhận</button>
            </div>
        </div>
    </div>
</div>

<script src="./assets/js/plugins.min.js"></script>
<script src="./assets/js/ajax-mail.js"></script>
<script src="./assets/js/main.js"></script>
<script src="./assets/js/form-validate.js"></script>
<script src="./assets/js/address-select.js"></script>
<script src="./assets/js/confirm-dialog.js"></script>
</body>

</html>