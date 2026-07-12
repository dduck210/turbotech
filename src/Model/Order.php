<?php

namespace Codemoi\Model;

use Codemoi\Core\Database;

/**
 * Order (bill) + persisted-cart-line queries. Ported from `model/hoadon.php`
 * and the cart-persistence functions of `model/giohang.php`.
 */
class Order
{
    /**
     * Create a bill row, returning the new id.
     * Mirrors old `insert_bill(...)`.
     */
    public static function create(
        string $bill_code,
        int $id_user,
        string $user_name,
        string $full_name,
        string $address,
        string $phone,
        string $email,
        string $payment,
        string $order_date,
        string $total_amount,
        ?string $coupon_code = null,
        int $discount_amount = 0
    ): string {
        $sql = "INSERT INTO bill(bill_code,id_user, user_name, full_name, address, phone, email, payment, order_date, total_amount, coupon_code, discount_amount) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        return Database::executeReturnId(
            $sql,
            $bill_code,
            $id_user,
            $user_name,
            $full_name,
            $address,
            $phone,
            $email,
            $payment,
            $order_date,
            $total_amount,
            $coupon_code,
            $discount_amount
        );
    }

    /**
     * Single bill by id.
     * Mirrors old `loadone_bill($id_bill)`.
     *
     * @return array|false
     */
    public static function one(int $id_bill)
    {
        $sql = "SELECT * FROM bill WHERE id_bill = ?";
        return Database::queryOne($sql, $id_bill);
    }

    /**
     * All bills placed by a user.
     * Mirrors old `loadall_bill($id_user)`.
     */
    public static function allByUser(int $id_user): array
    {
        $sql = "SELECT * FROM bill WHERE id_user = ?";
        return Database::query($sql, $id_user);
    }

    /**
     * Whether a user has an actually-delivered (status = 3) order
     * containing this product — the bar for letting them leave a review,
     * so reviews only ever come from verified purchasers. Delivered rather
     * than just paid/placed, since admin's update_bill.php only sets
     * status = 3 once the order has genuinely been handed to the customer.
     */
    public static function hasDeliveredPurchase(int $id_user, int $id_pro): bool
    {
        $sql = "SELECT 1 FROM cart c
                INNER JOIN bill b ON c.id_bill = b.id_bill
                WHERE c.id_user = ? AND c.id_pro = ? AND b.status = 3
                LIMIT 1";
        return Database::queryOne($sql, $id_user, $id_pro) !== false;
    }

    /**
     * Persisted cart lines for a bill.
     * Mirrors old `loadall_cart($idbill)` / `load_cart_all($idbill)`.
     */
    public static function items(int $idbill): array
    {
        $sql = "SELECT * FROM cart WHERE id_bill = ?";
        return Database::query($sql, $idbill);
    }

    /**
     * Count of persisted cart lines for a bill.
     * Mirrors old `loadall_countcart($idbill)` (`model/giohang.php:163`).
     */
    public static function itemCount(int $idbill): int
    {
        $sql = "SELECT * FROM cart WHERE id_bill = ?";
        return count(Database::query($sql, $idbill));
    }

    /**
     * Persist one cart line against a bill.
     * Mirrors old `insert_cart(...)` (`model/giohang.php:152`).
     */
    public static function addItem(
        int $id_user,
        string $user_name,
        int $id_pro,
        string $img_pro,
        string $name_pro,
        string $price,
        int $quantity,
        string $total_amount,
        int $idbill
    ): void {
        $sql = "INSERT INTO cart(id_user, user_name, id_pro, img_pro, name_pro, price_pro, quantity, total_amount, id_bill) values (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        Database::execute(
            $sql,
            $id_user,
            $user_name,
            $id_pro,
            $img_pro,
            $name_pro,
            $price,
            $quantity,
            $total_amount,
            $idbill
        );
    }

    /**
     * Cancel an order (status -> 4, "Đã hủy"), but only if it belongs to
     * the requesting user AND is still in the "Đơn hàng mới" (0) state —
     * both checks are baked into the WHERE clause so this is an atomic,
     * ownership-safe operation (a tampered `id_bill` for someone else's
     * order, or an order that's already being processed/shipped/cancelled,
     * simply matches zero rows instead of silently succeeding).
     *
     * @return bool True if the order was actually cancelled.
     */
    public static function cancel(int $id_bill, int $id_user): bool
    {
        $sql = "UPDATE bill SET status = 4 WHERE id_bill = ? AND id_user = ? AND status = 0";
        return Database::execute($sql, $id_bill, $id_user) > 0;
    }

    /**
     * All bills across all users, optionally filtered by status/keyword
     * (matches `full_name`/`phone`)/date range. Mirrors old
     * `admin/model/bill.php::loadall_bill($iduser = 0, ...)` with `$iduser`
     * fixed at 0 (the admin list always shows every user's orders — the
     * `$iduser` filter only matters for `allByUser()`'s client-facing use).
     */
    public static function allAdmin(int $status = -1, string $keyword = '', string $fromDate = '', string $toDate = ''): array
    {
        $sql = "SELECT * FROM bill WHERE 1";
        $args = [];
        if ($status > -1) {
            $sql .= " AND status = ?";
            $args[] = $status;
        }
        if ($keyword !== '') {
            $sql .= " AND (full_name LIKE ? OR phone LIKE ?)";
            $args[] = "%" . $keyword . "%";
            $args[] = "%" . $keyword . "%";
        }
        if ($fromDate !== '') {
            $sql .= " AND DATE(order_date) >= ?";
            $args[] = $fromDate;
        }
        if ($toDate !== '') {
            $sql .= " AND DATE(order_date) <= ?";
            $args[] = $toDate;
        }
        $sql .= " ORDER BY id_bill DESC";

        return Database::query($sql, ...$args);
    }

    /**
     * Admin-panel status update (order status + payment status together).
     * Mirrors old `admin/model/bill.php::update_bill(...)`. Unlike
     * `cancel()`, this is an unconditional admin override — no
     * ownership/current-state guard, since the admin controller already
     * enforces the valid from-state per transition (approve/ship/cancel).
     */
    public static function updateStatus(int $idBill, string $status, string $statusPay): void
    {
        Database::execute("UPDATE bill SET status = ?, status_pay = ? WHERE id_bill = ?", $status, $statusPay, $idBill);
    }

    /**
     * Human-readable label for a bill status code.
     * Mirrors old `get_stt($n)`.
     */
    public static function statusLabel(int $n): string
    {
        switch ($n) {
            case '0':
                return "Đơn hàng mới";
            case '1':
                return "Đang xử lí";
            case '2':
                return "Đang giao hàng";
            case '3':
                return "Đã giao hàng";
            case '4':
                return "Đã hủy";
            default:
                return "Đơn hàng mới";
        }
    }
}
