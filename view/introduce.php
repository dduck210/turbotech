<!-- Breadcrumb -->
<div class="border-b border-ink-200 py-4">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <ul class="flex items-center gap-2 text-sm text-ink-500">
            <li><a class="hover:text-brand-600" href="index.php">Trang chủ</a></li>
            <li aria-hidden="true">/</li>
            <li class="font-medium text-ink-900">Giới thiệu</li>
        </ul>
    </div>
</div>
<!-- Breadcrumb End Here -->

<!-- Editorial article layout: numbered sections + thin rule dividers (was: stacked glass cards) -->
<form action="index.php?act=introduce" method="post">
<?= \Codemoi\Core\Csrf::field() ?>
    <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8 md:py-20">
        <div class="text-center">
            <span class="text-serif-accent text-xs font-semibold uppercase tracking-[0.2em]">Turbotech</span>
            <h1 class="mt-2 font-heading text-3xl font-semibold text-ink-900 sm:text-4xl">Giới thiệu về Turbotech</h1>
            <span class="mt-5 inline-block h-px w-16 bg-brand-500"></span>
        </div>

        <div class="mt-12 space-y-12 divide-y divide-ink-200">
            <section>
                <div class="flex items-baseline gap-4">
                    <span class="font-heading text-3xl font-semibold text-brand-500">01</span>
                    <h2 class="font-heading text-xl font-semibold text-ink-900">Lịch Sử Hình Thành</h2>
                </div>
                <p class="mt-4 text-base leading-relaxed text-ink-700">
                    Trong kỷ nguyên số hóa mạnh mẽ hiện nay, Internet đã trở thành một phần không thể thiếu trong cuộc
                    sống. Người tiêu dùng ngày càng có xu hướng tìm kiếm, tham khảo thông tin và mua sắm trực tuyến, đặc
                    biệt là đối với các sản phẩm công nghệ có giá trị cao như Máy tính (PC) và Laptop.
                    <br><br>
                    Chính vì thế, website Turbotech ra đời với sứ mệnh mang đến cho khách hàng một không gian mua sắm công
                    nghệ an tâm tuyệt đối. Chúng tôi chuyên cung cấp đa dạng các dòng Laptop, Máy tính đồng bộ, PC Gaming,
                    PC Đồ họa và linh kiện máy tính chính hãng với mức giá cạnh tranh nhất thị trường, đảm bảo uy tín và
                    chất lượng hàng đầu.
                </p>
            </section>

            <section class="pt-12">
                <div class="flex items-baseline gap-4">
                    <span class="font-heading text-3xl font-semibold text-brand-500">02</span>
                    <h2 class="font-heading text-xl font-semibold text-ink-900">Tầm Nhìn</h2>
                </div>
                <p class="mt-4 text-base leading-relaxed text-ink-700">
                    Trở thành Website bán lẻ máy tính, laptop và linh kiện chính hãng có chất lượng sản phẩm và dịch vụ
                    hàng đầu trong nước.
                    <br><br>
                    Phát triển thương hiệu ngày càng lớn mạnh bằng cách tập trung chiến lược vào việc "Nâng Tầm Dịch Vụ
                    Khách Hàng", nhằm mang đến những trải nghiệm mua sắm và hậu mãi hoàn hảo, trọn vẹn nhất.
                </p>
            </section>

            <section class="pt-12">
                <div class="flex items-baseline gap-4">
                    <span class="font-heading text-3xl font-semibold text-brand-500">03</span>
                    <h2 class="font-heading text-xl font-semibold text-ink-900">Lợi ích của website đối với người đang quan tâm đến các sản phẩm linh kiện</h2>
                </div>
                <ul class="mt-4 space-y-3 text-base leading-relaxed text-ink-700">
                    <li class="flex gap-3"><i class="fa-solid fa-angles-right mt-1.5 text-xs text-brand-500"></i><span>Tạo ra một nguồn thông tin đáng tin cậy: Cung cấp đầy đủ, chính xác thông tin về các dòng máy, xu hướng.</span></li>
                    <li class="flex gap-3"><i class="fa-solid fa-angles-right mt-1.5 text-xs text-brand-500"></i><span>Tra cứu thông tin trực quan, dễ dàng: Khách hàng có thể nhanh chóng tham khảo mọi chi tiết về chiếc PC/Laptop mình đang quan tâm bao gồm: giá cả, thông số cấu hình chi tiết (CPU, RAM, VGA, Ổ cứng), chế độ bảo hành, tình trạng máy (mới 100% hay hàng lướt)... cũng như dễ dàng so sánh các cấu hình với nhau.</span></li>
                    <li class="flex gap-3"><i class="fa-solid fa-angles-right mt-1.5 text-xs text-brand-500"></i><span>Minh bạch về chi phí: Giúp người dùng nắm rõ thông tin về giá máy, chi phí nâng cấp linh kiện hoặc các dịch vụ cài đặt, vệ sinh máy tính muốn thực hiện mà không lo phát sinh chi phí ẩn.</span></li>
                    <li class="flex gap-3"><i class="fa-solid fa-angles-right mt-1.5 text-xs text-brand-500"></i><span>Mua sắm tiện lợi, tiết kiệm thời gian: Website tích hợp hệ thống đặt hàng thông minh, giúp khách hàng sở hữu chiếc Laptop hay bộ PC ưng ý một cách dễ dàng, nhanh chóng ngay tại nhà, tiết kiệm công sức đi lại so với việc phải tìm kiếm thủ công tại các cửa hàng truyền thống.</span></li>
                </ul>
            </section>
        </div>
    </div>
</form>
