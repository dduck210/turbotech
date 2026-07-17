@extends('layouts.app')

@section('content')
<span class="eyebrow">Bước cuối cùng</span>
<h1 class="mb-6 mt-2 font-heading text-2xl font-semibold text-ink-900">Thông tin đặt hàng</h1>

<div class="grid grid-cols-1 gap-8 lg:grid-cols-[1fr_360px]">
    <form action="{{ route('checkout.confirm') }}" method="post" class="space-y-4 rounded-md border border-ink-200 bg-white p-6">
        @csrf
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Họ và tên người nhận</label>
            <input type="text" name="full_name" value="{{ old('full_name', auth()->user()->full_name) }}" required class="input-boutique">
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="mb-1 block text-sm font-medium text-ink-700">Tỉnh/thành phố</label>
                <input type="text" name="province" required class="input-boutique">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-ink-700">Xã/phường</label>
                <input type="text" name="ward" required class="input-boutique">
            </div>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Địa chỉ chi tiết</label>
            <input type="text" name="address_detail" required class="input-boutique">
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="mb-1 block text-sm font-medium text-ink-700">Số điện thoại</label>
                <input type="text" name="phone" required pattern="[0-9]{10}" class="input-boutique">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-ink-700">Email</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email_user) }}" required class="input-boutique">
            </div>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-ink-700">Phương thức thanh toán</label>
            <select name="payment" required class="input-boutique">
                <option value="1">Thanh toán khi nhận hàng</option>
                <option value="2">Chuyển khoản ngân hàng</option>
            </select>
        </div>
        <button type="submit" class="btn-boutique w-full rounded-md px-5 py-3 text-sm">Đặt hàng</button>
    </form>

    <div class="space-y-4">
        <div class="rounded-md border border-ink-200 bg-white p-4">
            <h3 class="eyebrow mb-3">Mã giảm giá</h3>
            <div class="flex gap-2">
                <input type="text" id="coupon-code" value="{{ $couponCode }}" placeholder="Nhập mã" class="input-boutique flex-1">
                <button type="button" id="apply-coupon" class="rounded-md border border-ink-300 px-4 py-2 text-sm font-medium transition-colors hover:border-brand-500 hover:text-brand-600">Áp dụng</button>
            </div>
            <p id="coupon-message" class="mt-2 text-xs text-ink-500"></p>
        </div>
        <div class="rounded-md border border-ink-200 bg-white p-4 text-sm">
            <div class="flex justify-between"><span>Tạm tính</span><span class="tabular-nums">{{ number_format($total) }}₫</span></div>
            <div class="flex justify-between text-emerald-600"><span>Giảm giá</span><span id="coupon-discount" class="tabular-nums">-{{ number_format($couponDiscount) }}₫</span></div>
            <div class="mt-2 flex justify-between border-t border-ink-200 pt-2 text-base font-semibold">
                <span>Tổng cộng</span><span id="grand-total" class="price">{{ number_format($total - $couponDiscount) }}₫</span>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('apply-coupon').addEventListener('click', function () {
    const code = document.getElementById('coupon-code').value;
    fetch('{{ route('checkout.coupon.apply') }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ coupon_code: code }),
    }).then(r => r.json()).then(data => {
        document.getElementById('coupon-message').textContent = data.message;
        if (data.success) {
            document.getElementById('coupon-discount').textContent = '-' + new Intl.NumberFormat('vi-VN').format(data.discount) + '₫';
            document.getElementById('grand-total').textContent = new Intl.NumberFormat('vi-VN').format(data.total) + '₫';
        }
    });
});
</script>
@endsection
