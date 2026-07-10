<?php

namespace Codemoi\Controller;

use Codemoi\Core\Controller;
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
        if (isset($_SESSION['errorMessage'])) {
            echo "<script type='text/javascript'>alert('" . $_SESSION['errorMessage'] . "');</script>";
            unset($_SESSION['errorMessage']);
        }

        // Re-validate the session's applied coupon against the current
        // cart on every page load, so a stale/expired code (or one that no
        // longer matches the cart contents) is silently dropped instead of
        // showing a discount that `confirm()` won't actually honor.
        $coupon = $this->resolveCoupon(Cart::total());

        $this->view('giohang/bill', [
            'couponCode' => $coupon['code'],
            'couponDiscount' => $coupon['discount'],
        ]);
    }

    public function pay(): void
    {
        $this->view('qr');
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
                echo '<script>alert("Bạn phải đăng nhập để đặt hàng!")</script>';
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
                $_SESSION['errorMessage'] = "Email không hợp lệ !";
                $check_error = 1;
            }
            if (self::validateMobile($phone) == 0) {
                $_SESSION['errorMessage'] = "Số điện thoại không đúng định dạng !";
                $check_error = 1;
            }
            if ($province === '' || $ward === '' || $address_detail === '') {
                $_SESSION['errorMessage'] = "Vui lòng nhập đầy đủ địa chỉ nhận hàng !";
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
                    $_SESSION['errorMessage'] = "Sản phẩm \"{$cart[1]}\" không đủ số lượng tồn kho, vui lòng cập nhật giỏ hàng!";
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
            $this->view('giohang/billconfirm', [
                'bill' => $bill,
                'cart_detail' => $cart_detail,
            ]);
        }
    }

    /**
     * Near-duplicate of `confirm()` used for direct `?act=viewbill` visits.
     * The old code referenced `$full_name`/`$address`/`$phone`/`$email`/
     * `$payment` here while the `$_POST` reads that fed them were commented
     * out (`index.php:392-396`) — a latent bug (Phase 05 fix, was flagged in
     * the plan's Open Questions). Reading them from `$_POST` the same way
     * `confirm()` does is clearly the original intent. In the real browser
     * flow (POST billconfirm -> redirect to `?act=viewbill`), the redirect is
     * a plain GET with no form body, so these all read as empty strings and
     * the cart is already empty (`$total_amount > 0` is false) — same
     * behavior as before for that path. This only changes behavior for the
     * previously-buggy scenario where `?act=viewbill` is hit directly with
     * POST data present and a non-empty cart.
     */
    public function viewbill(): void
    {
        $full_name = $_POST['full_name'] ?? '';
        $province = $_POST['province'] ?? '';
        $ward = $_POST['ward'] ?? '';
        $address_detail = $_POST['address_detail'] ?? '';
        $address = $address_detail !== '' ? "{$address_detail}, {$ward}, {$province}" : '';
        $phone = $_POST['phone'] ?? '';
        $email = $_POST['email'] ?? '';
        $payment = $_POST['payment'] ?? null;
        $bill_code = null;
        $idbill = $_SESSION['idbill'] ?? null;
        $total_amount = null;

        $user = Auth::user();

        if ($user) {
            $bill_code = substr(str_shuffle("123456789"), 0, 5);
            $order_date = date('Y/m/d h:i:s', time());
            $total_amount = Cart::total();

            if ($total_amount > 0) {
                foreach (Cart::items() as $cart) {
                    if (!Product::hasStock($cart[0], $cart[4])) {
                        $_SESSION['errorMessage'] = "Sản phẩm \"{$cart[1]}\" không đủ số lượng tồn kho, vui lòng cập nhật giỏ hàng!";
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

                $this->redirect('?act=viewbill');
            }

            foreach (Cart::items() as $cart) {
                Order::addItem($user['id_user'], $user['user_name'], $cart[0], $cart[2], $cart[1], $cart[3], $cart[4], $cart[5], $idbill);
                Product::decrementStock($cart[0], $cart[4]);
            }
        }
        Cart::clear();

        $bill = Order::one($idbill);
        $cart_detail = Order::items($idbill);

        if ($payment == 2) {
            $_SESSION['pay'] = [$payment, $total_amount, $bill_code];
            $this->redirect('view/qr.php');
        }

        $_SESSION['check'] = 1;

        if (($_SESSION['check'] ?? null) == 1 || $payment == 1) {
            $this->view('giohang/billconfirm', [
                'bill' => $bill,
                'cart_detail' => $cart_detail,
            ]);
        }
    }

    /** Mirrors old inline `function validate_mobile($mobile)` (`index.php:306-309`). */
    private static function validateMobile(string $mobile): int
    {
        return preg_match('/^[0-9]{10}+$/', (string) $mobile);
    }
}
