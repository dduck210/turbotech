<?php

namespace Codemoi\Controller;

use Codemoi\Core\Controller;
use Codemoi\Core\Seo;
use Codemoi\Model\Auth;
use Codemoi\Model\Cart;
use Codemoi\Model\Coupon;
use Codemoi\Model\Order;
use Codemoi\Model\Product;
use Codemoi\Model\User;

/**
 * Checkout flow. Ported from `index.php` cases `'bill'` (`:278-286`),
 * `'pay'` (`:287-289`), `'billconfirm'` (`:290-372`) and `'viewbill'`
 * (`:386-425`).
 *
 * `billconfirm`/`viewbill` keep their near-duplicate order-insert logic
 * separate rather than collapsing them (Phase 05 handles cleanup — see
 * plan's "Open Questions"), to keep this switchover as low-risk as
 * possible.
 */
class CheckoutController extends Controller
{
    public function bill(): void
    {
        Seo::setTitle('Thông tin đặt hàng - Turbotech');
        Seo::setDescription('Điền thông tin giao hàng và thanh toán để hoàn tất đơn hàng tại Turbotech.');

        // Re-validate the session's applied coupon against the current
        // cart on every page load, so a stale/expired code (or one that no
        // longer matches the cart contents) is silently dropped instead of
        // showing a discount that `confirm()` won't actually honor.
        $coupon = $this->resolveCoupon(Cart::total());

        $this->view('cart/bill', [
            'couponCode' => $coupon['code'],
            'couponDiscount' => $coupon['discount'],
        ]);
    }

    /**
     * AJAX coupon-code apply on the billing page. Same full-page-wrapped
     * response constraint as CartController::edit() — result is pulled out
     * of an HTML-comment marker instead of parsed as JSON. Storing the
     * applied coupon in the session (rather than a hidden form field) means
     * the discount actually charged at `confirm()` can never be tampered
     * with by the client; it's always the last coupon this same session
     * successfully validated.
     */
    public function applyCoupon(): void
    {
        $code = trim($_POST['coupon_code'] ?? '');
        $total = Cart::total();

        if ($code === '') {
            unset($_SESSION['coupon']);
            echo '<!--COUPON_RESULT:' . json_encode(['success' => false, 'message' => 'Vui lòng nhập mã giảm giá.']) . ':END-->';
            return;
        }

        $validation = Coupon::validateForCart($code, Cart::items(), $total);

        if (!$validation['ok']) {
            unset($_SESSION['coupon']);
            echo '<!--COUPON_RESULT:' . json_encode(['success' => false, 'message' => $validation['message']]) . ':END-->';
            return;
        }

        $_SESSION['coupon'] = [
            'code' => $validation['coupon']['code'],
            'id_coupon' => (int) $validation['coupon']['id_coupon'],
        ];

        echo '<!--COUPON_RESULT:' . json_encode([
            'success' => true,
            'message' => $validation['message'],
            'code' => $validation['coupon']['code'],
            'discount' => $validation['discount'],
            'total' => $total - $validation['discount'],
        ]) . ':END-->';
    }

    public function removeCoupon(): void
    {
        unset($_SESSION['coupon']);
        echo '<!--COUPON_RESULT:' . json_encode(['success' => true, 'total' => Cart::total()]) . ':END-->';
    }

    /**
     * Re-validate the session's applied coupon (if any) against the
     * current cart at the moment of order creation — the coupon could have
     * expired, hit its usage limit, or the cart contents could have
     * changed since it was applied — and return the discount to actually
     * charge. Never trusts a discount amount computed earlier.
     *
     * @return array{code: string|null, discount: int, id_coupon: int|null}
     */
    private function resolveCoupon(int $orderTotal): array
    {
        if (!isset($_SESSION['coupon']['code'])) {
            return ['code' => null, 'discount' => 0, 'id_coupon' => null];
        }

        $validation = Coupon::validateForCart($_SESSION['coupon']['code'], Cart::items(), $orderTotal);

        if (!$validation['ok']) {
            unset($_SESSION['coupon']);
            return ['code' => null, 'discount' => 0, 'id_coupon' => null];
        }

        return [
            'code' => $validation['coupon']['code'],
            'discount' => $validation['discount'],
            'id_coupon' => (int) $validation['coupon']['id_coupon'],
        ];
    }

    /**
     * Create an order from the current cart + billing form, then branch on
     * payment method (cash=1 renders the confirmation directly; bank
     * transfer=2/QR=3 redirect to the QR payment page).
     */
    public function confirm(): void
    {
        $payment = null;
        $bill_code = null;
        $idbill = $_SESSION['idbill'] ?? null;
        $total_amount = Cart::total();

        if (isset($_POST['orderconfirm']) && $_POST['orderconfirm']) {
            if (!Auth::check()) {
                $_SESSION['flash_error'] = 'Bạn phải đăng nhập để đặt hàng!';
                $this->redirect('index.php?act=login');
            }

            $user = Auth::user();
            $bill_code = substr(str_shuffle("123456789"), 0, 5);
            $full_name = trim($_POST['full_name'] ?? '');
            $province = trim($_POST['province'] ?? '');
            $ward = trim($_POST['ward'] ?? '');
            $address_detail = trim($_POST['address_detail'] ?? '');
            $address = $address_detail !== '' ? "{$address_detail}, {$ward}, {$province}" : '';
            $phone = trim($_POST['phone'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $payment = $_POST['payment'] ?? null;
            $order_date = date('Y/m/d h:i:s', time());
            $check_error = 0;

            // NOTE: the old code checked validateMobile($phone) twice in a
            // row (the first check's error message even said "Email không
            // hợp lệ!") instead of ever actually validating the email —
            // fixed to check each field once, for real.
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['flash_error'] = "Email không hợp lệ !";
                $check_error = 1;
            }
            if (self::validateMobile($phone) == 0) {
                $_SESSION['flash_error'] = "Số điện thoại không đúng định dạng !";
                $check_error = 1;
            }
            if ($province === '' || $ward === '' || $address_detail === '') {
                $_SESSION['flash_error'] = "Vui lòng nhập đầy đủ địa chỉ nhận hàng !";
                $check_error = 1;
            }

            if ($check_error != 0) {
                $this->redirect('?act=bill');
            }

            if ($total_amount <= 0) {
                $this->redirect('?act=viewcart');
            }

            // Re-check stock right before the order is actually created —
            // it was already checked when each line was added to the cart,
            // but stock could have run out since then (e.g. another
            // customer bought the last units while this one was still
            // browsing). Without this, decrementStock()'s own guard below
            // would silently no-op while the order still gets created for
            // more units than are actually in stock.
            foreach (Cart::items() as $cart) {
                if (!Product::hasStock($cart[0], $cart[4])) {
                    $_SESSION['flash_error'] = "Sản phẩm \"{$cart[1]}\" không đủ số lượng tồn kho, vui lòng cập nhật giỏ hàng!";
                    $this->redirect('?act=viewcart');
                }
            }

            $coupon = $this->resolveCoupon($total_amount);
            $total_amount = $total_amount - $coupon['discount'];

            $idbill = Order::create(
                $bill_code,
                $user['id_user'],
                $user['user_name'],
                $full_name,
                $address,
                $phone,
                $email,
                $payment,
                $order_date,
                $total_amount,
                $coupon['code'],
                $coupon['discount']
            );
            $_SESSION['idbill'] = $idbill;

            if ($coupon['id_coupon'] !== null) {
                Coupon::incrementUsage($coupon['id_coupon']);
            }
            unset($_SESSION['coupon']);

            // Kept for parity with the old call graph (return value unused
            // even in the legacy code); model dropped the "not found" echo.
            User::byEmail($email);

            $title = "Thông báo đặt hàng thành công!";
            $content = "<h3>Xin chào, cảm ơn quý khách đặt hàng tại Turbotech.<br></h3>
                            <h4>Thông tin người nhận:</h4>
                            <p>Tên khách hàng: " . $full_name . "</p>
                            <p>Email: " . $email . "</p>
                            <p>Địa chỉ: " . $address . "</p>
                            <p>Số điện thoại: " . $phone . "</p>
                            <p>Ngày đặt hàng: " . $order_date . "</p>
                            <p>Tổng tiền: " . number_format($total_amount) . "₫</p>
                            ";
            $content .= "Chào mừng đến với  <a href='http://localhost/duan1-turbotech/index.php'>UltraPhone! </a>";

            $mail = new \Codemoi\Mail\Mailer();
            $mail->sendMail($title, $content, $email);
            $_SESSION['mail'] = $email;

            foreach (Cart::items() as $cart) {
                Order::addItem($user['id_user'], $user['user_name'], $cart[0], $cart[2], $cart[1], $cart[3], $cart[4], $cart[5], $idbill);
                Product::decrementStock($cart[0], $cart[4]);
            }
            Cart::clear();
        }

        $bill = Order::one($idbill);
        $cart_detail = Order::items($idbill);

        if ($payment == 2 || $payment == 3) {
            $_SESSION['pay'] = [$payment, $total_amount, $bill_code];
            $this->redirect('view/qr.php');
        }

        $_SESSION['check'] = 1;

        if (($_SESSION['check'] ?? null) == 1 || $payment == 1) {
            Seo::setTitle('Đặt hàng thành công - Turbotech');
            $this->view('cart/billconfirm', [
                'bill' => $bill,
                'cart_detail' => $cart_detail,
            ]);
        }
    }

    /**
     * Renders the confirmation for the order `confirm()` just created
     * (tracked via `$_SESSION['idbill']`). This used to also have its own
     * order-creation branch for a direct `?act=viewbill` hit, but nothing
     * in the app actually links or redirects here with order data —
     * `confirm()` renders the confirmation itself for cash orders and
     * redirects straight to `view/qr.php` for bank/QR ones — so that branch
     * was only ever reachable via a bare, uncredentialed GET. It also
     * self-redirected back into itself before it ever reached the
     * `Order::addItem()` line-item insert, so even a legitimate hit could
     * only ever produce a `bill` row with no line items. Removed rather
     * than patched: it had no real caller and no path that ended correctly.
     */
    public function viewbill(): void
    {
        $idbill = $_SESSION['idbill'] ?? null;
        $bill = $idbill !== null ? Order::one((int) $idbill) : false;
        $cart_detail = $idbill !== null ? Order::items((int) $idbill) : [];

        Seo::setTitle('Đặt hàng thành công - Turbotech');
        $this->view('cart/billconfirm', [
            'bill' => $bill,
            'cart_detail' => $cart_detail,
        ]);
    }

    /** Mirrors old inline `function validate_mobile($mobile)` (`index.php:306-309`). */
    private static function validateMobile(string $mobile): int
    {
        return preg_match('/^[0-9]{10}+$/', (string) $mobile);
    }
}
