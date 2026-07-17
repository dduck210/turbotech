<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\CartService;
use App\Services\CouponService;
use App\Services\VietQrService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * Ported from `Codemoi\Controller\CheckoutController` (legacy
 * `src/Controller/CheckoutController.php`).
 */
class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartService $cart,
        private readonly CouponService $coupons,
    ) {}

    public function show()
    {
        $total = $this->cart->total();
        $resolved = $this->resolveCoupon($total);

        return view('cart.bill', [
            'couponCode' => $resolved['coupon']?->code,
            'couponDiscount' => $resolved['discount'],
            'total' => $total,
        ]);
    }

    public function applyCoupon(Request $request)
    {
        $code = trim((string) $request->input('coupon_code', ''));
        $total = $this->cart->total();

        if ($code === '') {
            session()->forget('coupon');

            return response()->json(['success' => false, 'message' => 'Vui lòng nhập mã giảm giá.']);
        }

        $validation = $this->coupons->validateForCart($code, $this->cart->items(), $total);

        if (! $validation['ok']) {
            session()->forget('coupon');

            return response()->json(['success' => false, 'message' => $validation['message']]);
        }

        session(['coupon' => ['code' => $validation['coupon']->code, 'id_coupon' => $validation['coupon']->id_coupon]]);

        return response()->json([
            'success' => true,
            'message' => $validation['message'],
            'code' => $validation['coupon']->code,
            'discount' => $validation['discount'],
            'total' => $total - $validation['discount'],
        ]);
    }

    public function removeCoupon()
    {
        session()->forget('coupon');

        return response()->json(['success' => true, 'total' => $this->cart->total()]);
    }

    /**
     * Re-validates the session's applied coupon against the CURRENT cart —
     * it could have expired, hit its limit, or the cart could have changed
     * since it was applied. Never trusts a discount computed earlier.
     */
    private function resolveCoupon(int $orderTotal): array
    {
        $sessionCoupon = session('coupon');
        if (! $sessionCoupon) {
            return ['coupon' => null, 'discount' => 0];
        }

        $validation = $this->coupons->validateForCart($sessionCoupon['code'], $this->cart->items(), $orderTotal);
        if (! $validation['ok']) {
            session()->forget('coupon');

            return ['coupon' => null, 'discount' => 0];
        }

        return ['coupon' => $validation['coupon'], 'discount' => $validation['discount']];
    }

    public function confirm(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'full_name' => ['required', 'string'],
            'province' => ['required', 'string'],
            'ward' => ['required', 'string'],
            'address_detail' => ['required', 'string'],
            'phone' => ['required', 'regex:/^(\+?84|0)\d{9,10}$/'],
            'email' => ['required', 'email'],
            'payment' => ['required', 'in:1,2,3'],
        ], [
            'phone.regex' => 'Số điện thoại không đúng định dạng !',
        ]);

        $address = trim($data['address_detail']).', '.trim($data['ward']).', '.trim($data['province']);
        $totalAmount = $this->cart->total();

        if ($totalAmount <= 0) {
            return redirect()->route('cart.view');
        }

        // Re-check stock right before creating the order — it was already
        // checked at add-to-cart time, but stock could have run out since
        // (another customer bought the last units while this one browsed).
        foreach ($this->cart->items() as $line) {
            $product = Product::find($line['id_pro']);
            if (! $product || ! $product->hasStock($line['quantity'])) {
                return redirect()->route('cart.view')
                    ->with('flash_error', 'Sản phẩm "'.$line['name_pro'].'" không đủ số lượng tồn kho, vui lòng cập nhật giỏ hàng!');
            }
        }

        $resolved = $this->resolveCoupon($totalAmount);
        $billCode = $this->generateUniqueBillCode();

        $order = DB::transaction(function () use ($data, $user, $address, $billCode, $totalAmount, $resolved) {
            // Re-validated against `resolveCoupon()` moments ago, but the
            // authoritative check is this atomic increment, done here
            // (inside the same transaction as the order it discounts) so a
            // second checkout racing on the same near-limit coupon can't
            // both pass validation and both get the discount — the guard is
            // CouponService::incrementUsage()'s own conditional UPDATE, not
            // a fresh SELECT, which is exactly the check-then-act gap that
            // would otherwise let the cap be exceeded by one.
            $discount = 0;
            $coupon = $resolved['coupon'];
            if ($coupon && $this->coupons->incrementUsage($coupon->id_coupon) > 0) {
                $discount = $resolved['discount'];
            } else {
                $coupon = null;
            }
            $finalTotal = $totalAmount - $discount;

            $order = Order::create([
                'bill_code' => $billCode,
                // id_pro/name_pro are dead columns from before the
                // multi-product-per-order redesign (real line items live in
                // `cart`/OrderItem) — every existing row has them at 0/'',
                // and they're NOT NULL with no default, so they must be
                // set explicitly here now that Laravel's MySQL connector
                // enforces strict mode (the legacy PDO connection didn't,
                // which is why its equivalent INSERT could omit them).
                'id_pro' => 0,
                'name_pro' => '',
                'id_user' => $user->id_user,
                'user_name' => $user->user_name,
                'full_name' => $data['full_name'],
                'address' => $address,
                'phone' => $data['phone'],
                'email' => $data['email'],
                'payment' => $data['payment'],
                'order_date' => now(),
                'total_amount' => $finalTotal,
                'status' => Order::STATUS_NEW,
                'status_pay' => 0,
                'coupon_code' => $coupon?->code,
                'id_coupon' => $coupon?->id_coupon,
                'discount_amount' => $discount,
            ]);

            foreach ($this->cart->items() as $line) {
                OrderItem::create([
                    'id_user' => $user->id_user,
                    'user_name' => $user->user_name,
                    'id_pro' => $line['id_pro'],
                    'img_pro' => $line['img_pro'],
                    'name_pro' => $line['name_pro'],
                    'price_pro' => $line['price'],
                    'quantity' => $line['quantity'],
                    'total_amount' => $line['total'],
                    'id_bill' => $order->id_bill,
                ]);

                Product::where('id_pro', $line['id_pro'])
                    ->where('stock', '>=', $line['quantity'])
                    ->decrement('stock', $line['quantity']);
            }

            return $order;
        });

        session()->forget('coupon');

        // The order is already safely committed at this point — an SMTP
        // hiccup is a notification failure, not a checkout failure, and
        // shouldn't turn a successful order into a 500 the customer might
        // react to by placing a duplicate one.
        try {
            Mail::html(
                '<h3>Xin chào, cảm ơn quý khách đặt hàng tại Turbotech.<br></h3>'.
                '<p>Tên khách hàng: '.e($data['full_name']).'</p>'.
                '<p>Địa chỉ: '.e($address).'</p>'.
                '<p>Tổng tiền: '.number_format($order->total_amount).'₫</p>',
                function ($message) use ($data) {
                    $message->to($data['email'])->subject('Thông báo đặt hàng thành công!');
                }
            );
        } catch (\Throwable $e) {
            Log::error('Order confirmation email failed to send for bill '.$order->bill_code.': '.$e->getMessage());
        }

        $this->cart->clear();
        session(['idbill' => $order->id_bill]);

        if (in_array((int) $data['payment'], [2, 3], true)) {
            session(['pay' => [$data['payment'], $order->total_amount, $billCode]]);

            return redirect()->route('checkout.qr');
        }

        return redirect()->route('checkout.confirmation');
    }

    /**
     * `bill_code` has no DB-level unique constraint (existing schema,
     * unowned by this migration), and 5-digit-no-repeat codes from a
     * 9-digit pool (15120 possibilities) hit a non-trivial collision
     * chance well before the codebase would otherwise need to care —
     * checked against the table and regenerated on the rare collision
     * instead of trusting str_shuffle alone.
     */
    private function generateUniqueBillCode(): string
    {
        for ($i = 0; $i < 10; $i++) {
            $code = substr(str_shuffle('123456789'), 0, 5);
            if (! Order::where('bill_code', $code)->exists()) {
                return $code;
            }
        }

        return substr(str_shuffle('123456789'), 0, 5).random_int(10, 99);
    }

    /** Renders the confirmation for the order just created (session-tracked id_bill). */
    public function confirmation()
    {
        $idBill = session('idbill');
        $order = $idBill ? Order::find($idBill) : null;

        return view('cart.confirmation', [
            'order' => $order,
            'items' => $order ? $order->items : collect(),
        ]);
    }

    public function qr(VietQrService $vietQr)
    {
        $pay = session('pay');
        if (! $pay) {
            return redirect()->route('cart.view');
        }

        [$payment, $amount, $billCode] = $pay;

        return view('cart.qr', [
            'qrUrl' => $vietQr->qrUrl($amount, $billCode),
            'memo' => $vietQr->transferMemo($billCode),
            'amount' => $amount,
        ]);
    }

    public function confirmTransfer()
    {
        session()->forget('pay');

        return redirect()->route('checkout.confirmation');
    }
}
