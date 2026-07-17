@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-3xl text-center">
    <span class="eyebrow justify-center">Câu chuyện của chúng tôi</span>
    <h1 class="mb-5 mt-2 font-heading text-3xl font-semibold text-ink-900">Giới thiệu Turbotech</h1>
    <p class="text-base leading-relaxed text-ink-700">
        Turbotech là đơn vị cung cấp laptop gaming và PC hiệu năng cao chính hãng, cấu hình mạnh mẽ,
        giá cạnh tranh. Miễn phí vận chuyển toàn quốc, bảo hành chính hãng 12 tháng.
    </p>
</div>

<div class="mx-auto mt-14 grid max-w-4xl grid-cols-1 gap-6 sm:grid-cols-3">
    <div class="card-boutique rounded-md p-6 text-center">
        <i class="fas fa-shield-alt text-2xl text-brand-600"></i>
        <h3 class="mt-3 font-heading text-lg font-semibold text-ink-900">Chính hãng 100%</h3>
        <p class="mt-1.5 text-sm text-ink-600">Sản phẩm nhập khẩu, tem bảo hành đầy đủ.</p>
    </div>
    <div class="card-boutique rounded-md p-6 text-center">
        <i class="fas fa-truck text-2xl text-brand-600"></i>
        <h3 class="mt-3 font-heading text-lg font-semibold text-ink-900">Giao hàng toàn quốc</h3>
        <p class="mt-1.5 text-sm text-ink-600">Miễn phí vận chuyển, đóng gói cẩn thận.</p>
    </div>
    <div class="card-boutique rounded-md p-6 text-center">
        <i class="fas fa-headset text-2xl text-brand-600"></i>
        <h3 class="mt-3 font-heading text-lg font-semibold text-ink-900">Hỗ trợ tận tâm</h3>
        <p class="mt-1.5 text-sm text-ink-600">Đội ngũ tư vấn sẵn sàng đồng hành cùng bạn.</p>
    </div>
</div>
@endsection
