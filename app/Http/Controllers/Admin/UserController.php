<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Ported from `Codemoi\Controller\Admin\UserController` (legacy
 * `src/Controller/Admin/UserController.php`). Last-admin-lockout enforced
 * via `UserPolicy::demote()`/`delete()`, checked through `authorize()`
 * (not an inline controller if-statement) so it can't be bypassed from a
 * different entry point.
 */
class UserController extends Controller
{
    public function index()
    {
        return view('admin.user.index', ['users' => User::orderByDesc('id_user')->get()]);
    }

    public function edit(int $id)
    {
        return view('admin.user.edit', ['user' => User::findOrFail($id)]);
    }

    public function update(Request $request, int $id)
    {
        $target = User::findOrFail($id);

        $data = $request->validate([
            'user_name' => ['required', 'string'],
            'full_name' => ['required', 'string'],
            'email_user' => ['required', 'email'],
            'role' => ['required', 'in:0,1'],
            'password' => ['nullable', 'string', 'min:6'],
        ], [
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự !',
        ]);

        if (! $request->user()->can('demote', [$target, (int) $data['role']])) {
            return back()->withErrors(['role' => 'Không thể hạ quyền quản trị viên cuối cùng !']);
        }

        $update = [
            'user_name' => $data['user_name'],
            'full_name' => $data['full_name'],
            'email_user' => $data['email_user'],
            'role' => $data['role'],
        ];
        if (! empty($data['password'])) {
            $update['password'] = Hash::make($data['password']);
        }

        $target->update($update);

        return redirect()->route('admin.users.index')->with('flash_success', 'Cập nhật tài khoản thành công!');
    }

    public function destroy(Request $request, int $id)
    {
        $target = User::findOrFail($id);

        if (! $request->user()->can('delete', $target)) {
            return redirect()->route('admin.users.index')->with('flash_error', 'Không thể xóa quản trị viên cuối cùng !');
        }

        $target->delete();

        return redirect()->route('admin.users.index')->with('flash_success', 'Xoá tài khoản thành công!');
    }
}
