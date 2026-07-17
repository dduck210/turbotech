@extends('admin.layouts.app')

@section('page-title', 'Sửa tài khoản')

@section('content')
<form action="{{ route('admin.users.update', $user->id_user) }}" method="post" class="max-w-lg space-y-4 rounded-md border border-ink-300 bg-white p-6 shadow-sm">
    @csrf @method('PUT')
    <div>
        <label class="mb-1 block text-sm font-medium text-ink-700">Tên đăng nhập</label>
        <input type="text" name="user_name" value="{{ $user->user_name }}" required class="input-boutique">
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-ink-700">Họ tên</label>
        <input type="text" name="full_name" value="{{ $user->full_name }}" required class="input-boutique">
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-ink-700">Email</label>
        <input type="email" name="email_user" value="{{ $user->email_user }}" required class="input-boutique">
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-ink-700">Vai trò</label>
        <select name="role" class="input-boutique">
            <option value="0" @selected((int) $user->role === 0)>Khách hàng</option>
            <option value="1" @selected((int) $user->role === 1)>Quản trị viên</option>
        </select>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-ink-700">Mật khẩu mới (để trống nếu giữ nguyên)</label>
        <input type="password" name="password" class="input-boutique">
    </div>
    <button type="submit" class="btn-boutique rounded-md px-5 py-2.5 text-sm">Lưu</button>
</form>
@endsection
