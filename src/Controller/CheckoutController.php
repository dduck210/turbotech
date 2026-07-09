<?php

namespace Codemoi\Controller;

use Codemoi\Core\Controller;
use Codemoi\Model\Auth;
use Codemoi\Model\Cart;
use Codemoi\Model\Order;
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

        $this->view('giohang/bill');
    }

    public function pay(): void
    {
        $this->view('qr');
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
            $full_name = $_POST['full_name'] ?? '';
            $address = $_POST['address'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $email = $_POST['email'] ?? '';
            $payment = $_POST['payment'] ?? null;
            $order_date = date('Y/m/d h:i:s', time());
            $check_error = 0;

            if (self::validateMobile($phone) == 0) {
                $_SESSION['errorMessage'] = "Email không hợp lệ !";
                $check_error = 1;
            }
            if (self::validateMobile($phone) == 0) {
                $_SESSION['errorMessage'] = "Số điện thoại không đúng định dạng !";
                $check_error = 1;
            }

            if ($check_error != 0) {
                $this->redirect('?act=bill');
            }

            if ($total_amount <= 0) {
                $this->redirect('?act=viewcart');
            }

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
                $total_amount
            );
            $_SESSION['idbill'] = $idbill;

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

            $mail = new \Mailer();
            $mail->sendMail($title, $content, $email);
            $_SESSION['mail'] = $email;

            foreach (Cart::items() as $cart) {
                Order::addItem($user['id_user'], $user['user_name'], $cart[0], $cart[2], $cart[1], $cart[3], $cart[4], $cart[5], $idbill);
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
     * `$payment` here while they were commented-out `$_POST` reads
     * (`index.php:392-396`) — a latent bug flagged in the plan's Open
     * Questions. In the real browser flow (POST billconfirm -> redirect to
     * `?act=viewbill`), the cart is already empty by the time this runs, so
     * `$total_amount > 0` is false and the buggy `Order::create()` call is
     * never reached. We default these to null (instead of leaving them
     * undefined) purely to avoid emitting PHP warnings if this path is ever
     * hit directly — the resulting values passed to `Order::create()` are
     * identical (null either way).
     */
    public function viewbill(): void
    {
        $full_name = null;
        $address = null;
        $phone = null;
        $email = null;
        $payment = null;
        $bill_code = null;
        $idbill = $_SESSION['idbill'] ?? null;
        $total_amount = null;

        $user = Auth::user();

        if ($user) {
            $bill_code = substr(str_shuffle("123456789"), 0, 5);
            $order_date = date('Y/m/d h:i:s', time());
            $total_amount = Cart::total();

            if ($total_amount > 0) {
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
                    $total_amount
                );
                $_SESSION['idbill'] = $idbill;
                $this->redirect('?act=viewbill');
            }

            foreach (Cart::items() as $cart) {
                Order::addItem($user['id_user'], $user['user_name'], $cart[0], $cart[2], $cart[1], $cart[3], $cart[4], $cart[5], $idbill);
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
    private static function validateMobile($mobile): int
    {
        return preg_match('/^[0-9]{10}+$/', (string) $mobile);
    }
}
