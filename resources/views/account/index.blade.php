@extends('layouts.app')

@section('title', 'Tài khoản của tôi - Turbotech')

@section('content')
@php
    $user = auth()->user();
    $statusLabels = [
        0 => 'Đơn hàng mới', 1 => 'Đang xử lí', 2 => 'Đang giao hàng',
        3 => 'Đã giao hàng', 4 => 'Đã hủy',
    ];
@endphp

<div class="grid grid-cols-1 gap-8 lg:grid-cols-[240px_1fr]">
    <nav class="space-y-1 rounded-md border border-ink-200 bg-white p-4 text-sm">
        <a href="#account-details" class="block rounded-md px-3 py-2 font-medium text-ink-700 hover:bg-ink-100">Thông tin cá nhân</a>
        <a href="#account-orders" class="block rounded-md px-3 py-2 font-medium text-ink-700 hover:bg-ink-100">Đơn hàng của tôi</a>
        <a href="#account-password" class="block rounded-md px-3 py-2 font-medium text-ink-700 hover:bg-ink-100">Đổi mật khẩu</a>
    </nav>

    <div class="space-y-8">
        @if (session('cancelMessage'))
            <div class="rounded-md border border-brand-300 bg-brand-50 p-3 text-sm text-brand-700">{{ session('cancelMessage') }}</div>
        @endif

        <section id="account-details" class="rounded-md border border-ink-200 bg-white p-6">
            <h2 class="mb-4 font-heading text-lg font-semibold text-ink-900">Thông tin cá nhân</h2>
            <form action="{{ route('account.profile') }}" method="post" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="mb-1 block text-sm font-medium text-ink-700">Họ và tên</label>
                    <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" required
                        class="w-full rounded-md border border-ink-300 px-3.5 py-2.5 text-sm">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-ink-700">Email</label>
                    <input type="email" name="email_user" value="{{ old('email_user', $user->email_user) }}" required
                        class="w-full rounded-md border border-ink-300 px-3.5 py-2.5 text-sm">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-ink-700">Số điện thoại</label>
                    <input type="text" name="phone_user" value="{{ old('phone_user', $user->phone_user) }}" required
                        class="w-full rounded-md border border-ink-300 px-3.5 py-2.5 text-sm">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-ink-700">Tỉnh/thành phố</label>
                        <input type="text" name="province" required class="w-full rounded-md border border-ink-300 px-3.5 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-ink-700">Xã/phường</label>
                        <input type="text" name="ward" required class="w-full rounded-md border border-ink-300 px-3.5 py-2.5 text-sm">
                    </div>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-ink-700">Địa chỉ chi tiết</label>
                    <input type="text" name="address_detail" required class="w-full rounded-md border border-ink-300 px-3.5 py-2.5 text-sm">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-ink-700">Ảnh đại diện</label>
                    <input type="file" name="img_user" accept=".jpg,.jpeg,.png,.gif" class="w-full text-sm">
                </div>
                <button type="submit" class="btn-boutique rounded-md px-5 py-2.5 text-sm font-semibold">Lưu thay đổi</button>
            </form>
        </section>

        <section id="account-orders" class="rounded-md border border-ink-200 bg-white p-6">
            <h2 class="mb-4 font-heading text-lg font-semibold text-ink-900">Đơn hàng của tôi</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-ink-200 text-xs font-semibold uppercase text-ink-500">
                            <th class="py-2">Mã đơn</th>
                            <th class="py-2">Ngày đặt</th>
                            <th class="py-2">Tổng tiền</th>
                            <th class="py-2">Trạng thái</th>
                            <th class="py-2"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-ink-100">
                        @forelse ($orders as $order)
                            <tr>
                                <td class="py-3">{{ $order->bill_code }}</td>
                                <td class="py-3">{{ $order->order_date?->format('d/m/Y H:i') }}</td>
                                <td class="py-3 font-semibold text-brand-600">{{ number_format($order->total_amount) }}₫</td>
                                <td class="py-3">{{ $statusLabels[$order->status] ?? 'Đơn hàng mới' }}</td>
                                <td class="py-3">
                                    @if ((int) $order->status === 0)
                                        <form action="{{ route('account.orders.cancel') }}" method="post" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?');">
                                            @csrf
                                            <input type="hidden" name="id_bill" value="{{ $order->id_bill }}">
                                            <button type="submit" class="text-red-600 hover:underline">Hủy đơn</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-6 text-center text-ink-500">Bạn chưa có đơn hàng nào.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section id="account-password" class="rounded-md border border-ink-200 bg-white p-6">
            <h2 class="mb-4 font-heading text-lg font-semibold text-ink-900">Đổi mật khẩu</h2>
            <form action="{{ route('account.password') }}" method="post" class="space-y-4">
                @csrf
                <div>
                    <label class="mb-1 block text-sm font-medium text-ink-700">Mật khẩu hiện tại</label>
                    <input type="password" name="oldpass" required class="w-full rounded-md border border-ink-300 px-3.5 py-2.5 text-sm">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-ink-700">Mật khẩu mới</label>
                    <input type="password" name="newpass" required class="w-full rounded-md border border-ink-300 px-3.5 py-2.5 text-sm">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-ink-700">Nhập lại mật khẩu mới</label>
                    <input type="password" name="repass" required class="w-full rounded-md border border-ink-300 px-3.5 py-2.5 text-sm">
                </div>
                <button type="submit" class="btn-boutique rounded-md px-5 py-2.5 text-sm font-semibold">Đổi mật khẩu</button>
            </form>
        </section>
    </div>
</div>
@endsection
