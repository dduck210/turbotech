<!-- Breadcrumb -->
<div class="border-b border-ink-200 bg-white py-4">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <ul class="flex items-center gap-2 text-sm text-ink-500">
            <li><a class="hover:text-brand-600" href="index.php">Trang chủ</a></li>
            <li aria-hidden="true">/</li>
            <li class="font-medium text-ink-900">Liên hệ</li>
        </ul>
    </div>
</div>
<!-- Breadcrumb End Here -->

<div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 md:py-16">
    <div class="flex justify-center">
        <div class="w-full lg:w-3/4">
            <!-- form liên hệ-->
            <form action="index.php?act=contact" method="post" class="rounded-2xl border border-ink-200 bg-white p-6 shadow-sm sm:p-8">
                <h2 class="text-center font-heading text-2xl font-bold text-ink-900">Liên hệ Turbotech</h2>
                <div class="mt-6 grid grid-cols-1 gap-5">
                    <div>
                        <label for="lienhe-name" class="mb-1.5 block text-sm font-medium text-ink-700">Họ và tên</label>
                        <input type="text" id="lienhe-name" name="name"
                            class="block w-full rounded-lg border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                            placeholder="Nhập họ và tên của bạn" required>
                    </div>
                    <div>
                        <label for="lienhe-email" class="mb-1.5 block text-sm font-medium text-ink-700">Email</label>
                        <input type="email" id="lienhe-email" name="email"
                            class="block w-full rounded-lg border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                            placeholder="Nhập  địa chỉ email của bạn" required>
                    </div>
                    <div>
                        <label for="lienhe-phone" class="mb-1.5 block text-sm font-medium text-ink-700">Số điện thoại</label>
                        <input type="text" id="lienhe-phone" name="phone"
                            class="block w-full rounded-lg border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                            placeholder="Nhập số điện thoại của bạn" required>
                    </div>
                    <div>
                        <label for="lienhe-content" class="mb-1.5 block text-sm font-medium text-ink-700">Vấn đề</label>
                        <textarea id="lienhe-content" name="contennt" rows="5"
                            class="block w-full rounded-lg border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                            placeholder="Nhập câu hỏi của bạn..." required></textarea>
                    </div>
                    <div class="flex justify-center sm:justify-end">
                        <input type="submit" name="btn_contact" value="Gửi"
                            class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>