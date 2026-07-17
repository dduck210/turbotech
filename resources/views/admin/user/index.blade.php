@extends('admin.layouts.app')

@section('content')
<h1 class="mb-6 font-heading text-2xl font-semibold text-ink-900">Người dùng</h1>
<div class="overflow-x-auto rounded-md border border-ink-300 bg-white">
    <table class="w-full text-left text-sm">
        <thead>
            <tr class="border-b border-ink-300 text-xs font-semibold uppercase text-ink-500">
                <th class="p-4">Tên đăng nhập</th><th class="p-4">Họ tên</th><th class="p-4">Email</th><th class="p-4">Vai trò</th><th class="p-4"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-ink-200">
            @foreach ($users as $user)
                <tr>
                    <td class="p-4">{{ $user->user_name }}</td>
                    <td class="p-4">{{ $user->full_name }}</td>
                    <td class="p-4">{{ $user->email_user }}</td>
                    <td class="p-4">{{ (int) $user->role === 1 ? 'Quản trị viên' : 'Khách hàng' }}</td>
                    <td class="p-4">
                        <a href="{{ route('admin.users.edit', $user->id_user) }}" class="text-amber-600 hover:underline">Sửa</a>
                        <form action="{{ route('admin.users.destroy', $user->id_user) }}" method="post" class="inline" onsubmit="return confirm('Xóa tài khoản này?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="ml-3 text-red-600 hover:underline">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
