<!-- Breadcrumb -->
<div class="border-b border-ink-200 py-4">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <ul class="flex items-center gap-2 text-sm text-ink-500">
            <li><a class="hover:text-brand-600" href="index.php">Trang chủ</a></li>
            <li aria-hidden="true">/</li>
            <li class="font-medium text-ink-900">Liên hệ</li>
        </ul>
    </div>
</div>
<!-- Breadcrumb End Here -->

<!-- Two-column layout: contact info panel + form (was: single centered form card) -->
<div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 md:py-16">
    <div class="grid grid-cols-1 gap-10 lg:grid-cols-[1fr_1.4fr]">

        <div>
            <span class="text-serif-accent text-xs font-semibold uppercase tracking-[0.2em]">Turbotech</span>
            <h2 class="mt-2 font-heading text-2xl font-semibold text-ink-900 sm:text-3xl">Liên hệ với chúng tôi</h2>
            <span class="mt-4 block h-px w-16 bg-brand-500"></span>
            <p class="mt-5 text-sm leading-relaxed text-ink-600">
                Có thắc mắc về sản phẩm hoặc đơn hàng? Gửi cho chúng tôi vài dòng, đội ngũ Turbotech sẽ phản hồi sớm nhất có thể.
            </p>
            <ul class="mt-8 space-y-4 text-sm">
                <li class="flex items-start gap-3">
                    <i class="fa-solid fa-location-dot mt-0.5 w-4 text-brand-500"></i>
                    <span class="text-ink-700">Số 999 Gia lâm, Hà Nội</span>
                </li>
                <li class="flex items-center gap-3">
                    <i class="fa-solid fa-phone w-4 text-brand-500"></i>
                    <span class="text-ink-700">0987651234</span>
                </li>
                <li class="flex items-center gap-3">
                    <i class="fa-solid fa-envelope w-4 text-brand-500"></i>
                    <span class="text-ink-700">turbotech@gmail.com</span>
                </li>
            </ul>
        </div>

        <!-- form liên hệ-->
        <form action="index.php?act=contact" method="post" data-validate novalidate class="card-boutique rounded-md p-6 sm:p-8">
<?= \Codemoi\Core\Csrf::field() ?>
            <div class="grid grid-cols-1 gap-5">
                <div>
                    <label for="lienhe-name" class="mb-1.5 block text-sm font-medium text-ink-700">Họ và tên</label>
                    <input type="text" id="lienhe-name" name="name"
                        data-rules="required|min:2|max:100"
                        class="block w-full rounded-md border border-ink-300 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-500 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                        placeholder="Nhập họ và tên của bạn">
                </div>
                <div>
                    <label for="lienhe-email" class="mb-1.5 block text-sm font-medium text-ink-700">Email</label>
                    <input type="email" id="lienhe-email" name="email"
                        data-rules="required|email"
                        class="block w-full rounded-md border border-ink-300 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-500 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                        placeholder="Nhập  địa chỉ email của bạn">
                </div>
                <div>
                    <label for="lienhe-phone" class="mb-1.5 block text-sm font-medium text-ink-700">Số điện thoại</label>
                    <input type="text" id="lienhe-phone" name="phone"
                        data-rules="required|phone"
                        class="block w-full rounded-md border border-ink-300 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-500 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                        placeholder="Nhập số điện thoại của bạn">
                </div>
                <div>
                    <label for="lienhe-content" class="mb-1.5 block text-sm font-medium text-ink-700">Vấn đề</label>
                    <textarea id="lienhe-content" name="contennt" rows="5"
                        data-rules="required|min:10"
                        class="block w-full rounded-md border border-ink-300 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-500 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                        placeholder="Nhập câu hỏi của bạn..."></textarea>
                </div>
                <div class="flex justify-end">
                    <input type="submit" name="btn_contact" value="Gửi"
                        class="btn-boutique inline-flex cursor-pointer items-center justify-center gap-2 rounded-md px-6 py-2.5 text-sm font-semibold">
                </div>
            </div>
        </form>
    </div>
</div>
