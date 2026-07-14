<!-- phần active trang đang được hiển thị-->
<div class="border-b border-ink-200 py-4">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <ul class="flex items-center gap-2 text-sm text-ink-500">
            <li><a class="hover:text-brand-600" href="index.php">Trang chủ</a></li>
            <li aria-hidden="true">/</li>
            <li class="font-medium text-ink-900">Hỏi đáp</li>
        </ul>
    </div>
</div>
<!--end phần active trang đang được hiển thị-->

<!-- Two-column layout: intro panel + form (was: single centered form card) -->
<div class="py-12 md:py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-10 lg:grid-cols-[1fr_1.4fr]">

            <div>
                <span class="text-serif-accent text-xs font-semibold uppercase tracking-[0.2em]">Hỗ trợ khách hàng</span>
                <h2 class="mt-2 font-heading text-2xl font-semibold text-ink-900 sm:text-3xl">Bạn cần giải đáp?</h2>
                <span class="mt-4 block h-px w-16 bg-brand-500"></span>
                <p class="mt-5 text-sm leading-relaxed text-ink-600">
                    Gửi câu hỏi về sản phẩm, đơn hàng hoặc bảo hành — đội ngũ Turbotech sẽ phản hồi bạn qua email trong
                    thời gian sớm nhất.
                </p>
            </div>

            <!-- form hỏi đáp-->
            <form action="index.php?act=question" method="post" data-validate novalidate class="card-boutique rounded-md p-6 sm:p-8">
<?= \Codemoi\Core\Csrf::field() ?>
                <div class="grid grid-cols-1 gap-5">
                    <div>
                        <label for="hoidap-name" class="mb-1.5 block text-sm font-medium text-ink-700">Họ và tên</label>
                        <input type="text" id="hoidap-name" name="name"
                            data-rules="required|min:2|max:100"
                            class="block w-full rounded-md border border-ink-300 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-500 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                            placeholder="Nhập họ và tên của bạn">
                    </div>
                    <div>
                        <label for="hoidap-email" class="mb-1.5 block text-sm font-medium text-ink-700">Email</label>
                        <input type="email" id="hoidap-email" name="email"
                            data-rules="required|email"
                            class="block w-full rounded-md border border-ink-300 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-500 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                            placeholder="Nhập  địa chỉ email của bạn">
                    </div>
                    <div>
                        <label for="hoidap-phone" class="mb-1.5 block text-sm font-medium text-ink-700">Số điện thoại</label>
                        <input type="text" id="hoidap-phone" name="phone"
                            data-rules="required|phone"
                            class="block w-full rounded-md border border-ink-300 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-500 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                            placeholder="Nhập số điện thoại của bạn">
                    </div>
                    <div>
                        <label for="hoidap-content" class="mb-1.5 block text-sm font-medium text-ink-700">Câu hỏi</label>
                        <textarea id="hoidap-content" name="contennt" rows="5"
                            data-rules="required|min:10"
                            class="block w-full rounded-md border border-ink-300 bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-500 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                            placeholder="Nhập câu hỏi của bạn..."></textarea>
                    </div>
                    <div class="flex justify-end">
                        <input type="submit" name="btn_question" value="Gửi"
                            class="btn-boutique inline-flex cursor-pointer items-center justify-center gap-2 rounded-md px-6 py-2.5 text-sm font-semibold">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
