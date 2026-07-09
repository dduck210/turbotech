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
        $bill_code,
        $id_user,
        $user_name,
        $full_name,
        $address,
        $phone,
        $email,
        $payment,
        $order_date,
        $total_amount
    ): string {
        $sql = "INSERT INTO bill(bill_code,id_user, user_name, full_name, address, phone, email, payment, order_date, total_amount) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
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
            $total_amount
        );
    }

    /**
     * Single bill by id.
     * Mirrors old `loadone_bill($id_bill)`.
     *
     * @return array|false
     */
    public static function one($id_bill)
    {
        $sql = "SELECT * FROM bill WHERE id_bill = ?";
        return Database::queryOne($sql, $id_bill);
    }

    /**
     * All bills placed by a user.
     * Mirrors old `loadall_bill($id_user)`.
     */
    public static function allByUser($id_user): array
    {
        $sql = "SELECT * FROM bill WHERE id_user = ?";
        return Database::query($sql, $id_user);
    }

    /**
     * Persisted cart lines for a bill.
     * Mirrors old `loadall_cart($idbill)` / `load_cart_all($idbill)`.
     */
    public static function items($idbill): array
    {
        $sql = "SELECT * FROM cart WHERE id_bill = ?";
        return Database::query($sql, $idbill);
    }

    /**
     * Count of persisted cart lines for a bill.
     * Mirrors old `loadall_countcart($idbill)` (`model/giohang.php:163`).
     */
    public static function itemCount($idbill): int
    {
        $sql = "SELECT * FROM cart WHERE id_bill = ?";
        return count(Database::query($sql, $idbill));
    }

    /**
     * Persist one cart line against a bill.
     * Mirrors old `insert_cart(...)` (`model/giohang.php:152`).
     */
    public static function addItem(
        $id_user,
        $user_name,
        $id_pro,
        $img_pro,
        $name_pro,
        $price,
        $quantity,
        $total_amount,
        $idbill
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
    public static function cancel($id_bill, $id_user): bool
    {
        $sql = "UPDATE bill SET status = 4 WHERE id_bill = ? AND id_user = ? AND status = 0";
        return Database::execute($sql, $id_bill, $id_user) > 0;
    }

    /**
     * Human-readable label for a bill status code.
     * Mirrors old `get_stt($n)`.
     */
    public static function statusLabel($n): string
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
