<footer class="border-t-2 border-brand-500 bg-ink-900 text-ink-400">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 md:py-20 lg:px-8">
        <div class="grid grid-cols-1 gap-12 md:grid-cols-[1.3fr_1fr_1fr]">
            <!-- Brand + quick links -->
            <div>
                <a href="index.php" class="inline-flex items-center focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <img src="./assets/images/menu/logo/logo-wordmark-dark.svg" alt="Turbotech" class="h-9 w-auto" />
                </a>
                <p class="mt-5 max-w-sm text-sm leading-relaxed text-ink-500">
                    Turbotech ra đời với sứ mệnh mang đến cho khách hàng một không gian mua sắm công nghệ an tâm
                    tuyệt đối — laptop, PC và linh kiện chính hãng, giá cạnh tranh, dịch vụ tận tâm.
                </p>
                <p class="mt-6 text-xs uppercase tracking-widest text-ink-600">Turbotech &copy; 2026</p>
            </div>

            <!-- Quick links -->
            <div>
                <h3 class="font-heading text-lg font-semibold text-ink-50">Liên kết</h3>
                <span class="mt-2 block h-px w-10 bg-brand-500"></span>
                <nav class="mt-5 flex flex-col gap-3 text-sm">
                    <a href="index.php" class="w-fit transition-colors hover:text-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-500">Trang chủ</a>
                    <a href="index.php?act=product" class="w-fit transition-colors hover:text-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-500">Sản phẩm</a>
                    <a href="index.php?act=introduce" class="w-fit transition-colors hover:text-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-500">Giới thiệu</a>
                    <a href="index.php?act=contact" class="w-fit transition-colors hover:text-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-500">Liên hệ</a>
                    <a href="index.php?act=question" class="w-fit transition-colors hover:text-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-500">Hỏi đáp</a>
                </nav>
            </div>

            <!-- Contact -->
            <div>
                <h3 class="font-heading text-lg font-semibold text-ink-50">Liên hệ</h3>
                <span class="mt-2 block h-px w-10 bg-brand-500"></span>
                <ul class="mt-5 space-y-3 text-sm">
                    <li class="flex items-start gap-3">
                        <i class="fa-solid fa-location-dot mt-0.5 w-4 text-brand-400"></i>
                        <span>Số 999 Gia lâm, Hà Nội</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fa-solid fa-phone w-4 text-brand-400"></i>
                        <span>0987651234</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fa-solid fa-envelope w-4 text-brand-400"></i>
                        <a href="mailto:turbotech@gmail.com" class="transition-colors hover:text-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-500">turbotech@gmail.com</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>

<!-- Custom confirm dialog (replaces the native browser confirm() popup) —
     logic lives in assets/js/confirm-dialog.js; markup stays here so its
     classes are seen by Tailwind's content scan. -->
<div id="confirm-dialog-overlay" class="hidden fixed inset-0 z-50 bg-ink-900/40 p-4">
    <div class="flex h-full w-full items-center justify-center">
        <div class="card-boutique w-full max-w-sm rounded-md p-6">
            <div class="mb-4 flex items-center gap-3 border-b border-ink-200 pb-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-amber-500/15 text-amber-600">
                    <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
                </div>
                <h3 class="font-heading text-lg font-semibold text-ink-900">Xác nhận</h3>
            </div>
            <p id="confirm-dialog-message" class="mb-6 text-sm text-ink-600"></p>
            <div class="flex justify-end gap-3">
                <button type="button" id="confirm-dialog-cancel" class="rounded-md border border-ink-300 bg-ink-50 px-4 py-2 text-sm font-semibold text-ink-900 transition-colors hover:bg-ink-100 focus:outline-none focus:ring-2 focus:ring-brand-500">Hủy</button>
                <button type="button" id="confirm-dialog-ok" class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">Xác nhận</button>
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