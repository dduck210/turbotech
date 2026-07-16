<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Ported from `Codemoi\Controller\AccountController` (legacy
 * `src/Controller/AccountController.php`).
 */
class AccountController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $orders = Order::where('id_user', $user->id_user)->orderByDesc('id_bill')->get();

        return view('account.index', ['orders' => $orders]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'full_name' => ['required', 'string'],
            'email_user' => ['required', 'email'],
            'sex' => ['nullable', 'in:0,1'],
            'province' => ['required', 'string'],
            'ward' => ['required', 'string'],
            'address_detail' => ['required', 'string'],
            'phone_user' => ['required', 'string'],
        ], [
            'full_name.required' => 'Vui lòng nhập đầy đủ và đúng định dạng thông tin !',
            'email_user.email' => 'Vui lòng nhập đầy đủ và đúng định dạng thông tin !',
        ]);

        $address = trim($data['address_detail']).', '.trim($data['ward']).', '.trim($data['province']);

        $imgUser = $user->img_user;
        if ($request->hasFile('img_user')) {
            $file = $request->file('img_user');
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $extension = strtolower($file->getClientOriginalExtension());

            if (in_array($extension, $allowed, true) && ! preg_match('/\.(php|phtml|pht)/i', $file->getClientOriginalName())) {
                // Generated filename, not the client's — see
                // ProductController::store()'s upload handling for why
                // (prevents same-name-overwrite collisions between users).
                $storedName = 'user_'.Str::random(16).'.'.$extension;
                $file->move(public_path('uploads'), $storedName);
                $imgUser = $storedName;
            }
        }

        $user->update([
            'full_name' => $data['full_name'],
            'email_user' => $data['email_user'],
            'sex' => (int) ($data['sex'] ?? $user->sex),
            'address' => $address,
            'phone_user' => $data['phone_user'],
            'img_user' => $imgUser,
        ]);

        return back()->with('flash_success', 'Thay đổi thông tin thành công!');
    }

    public function changePassword(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'oldpass' => ['required'],
            'newpass' => ['required', 'string', 'min:6'],
            'repass' => ['required', 'same:newpass'],
        ], [
            'newpass.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự !',
            'repass.same' => 'Nhập lại mật khẩu không khớp !',
        ]);

        if (! Hash::check($request->input('oldpass'), $user->password)) {
            return back()->withErrors(['oldpass' => 'Mật khẩu hiện tại không đúng !']);
        }

        $user->update(['password' => Hash::make($request->input('newpass'))]);

        return back()->with('flash_success', 'Đổi mật khẩu thành công!');
    }

    /**
     * Ownership + current-state (status=0, "Đơn hàng mới") both enforced in
     * the WHERE clause — a tampered `id_bill` for someone else's order, or
     * an order already being processed, simply matches zero rows.
     */
    public function cancelOrder(Request $request)
    {
        $idBill = (int) $request->input('id_bill');
        $user = $request->user();

        $order = Order::where('id_bill', $idBill)
            ->where('id_user', $user->id_user)
            ->where('status', Order::STATUS_NEW)
            ->first();

        if (! $order) {
            return back()->with('cancelMessage', 'Không thể hủy đơn hàng này (đơn không tồn tại hoặc đã được xử lý).');
        }

        app(\App\Services\OrderCancellationService::class)->cancel($order);

        // #account-orders so the page reopens on the Orders tab instead of
        // resetting to the dashboard — see the hash-handling script in
        // resources/views/account/index.blade.php.
        return redirect(route('account.index').'#account-orders')
            ->with('cancelMessage', 'Đã hủy đơn hàng thành công.');
    }
}
