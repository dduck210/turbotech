<?php

namespace Codemoi\Model;

use Codemoi\Core\Database;

/**
 * Discount-code validation and redemption for checkout. The admin-side
 * CRUD (create/edit/list/delete) lives in the old procedural
 * `admin/model/coupon.php` against the same `coupons` table — this class
 * is only the client-facing "is this code usable right now" + "apply it"
 * half of the feature.
 */
class Coupon
{
    /** @return array|false */
    public static function findActiveByCode(string $code)
    {
        $sql = "SELECT * FROM coupons WHERE code = ? AND status = 1";
        return Database::queryOne($sql, $code);
    }

    /**
     * Validate a code against the current cart and compute its discount.
     * Centralizes every rule (active, in-date, usage limit, minimum order
     * value, single-product restriction) so checkout can't be tricked by
     * skipping straight to order-creation with a stale/invalid code.
     *
     * @param array $cartItems Cart::items() tuples: [0]=id_pro, ...
     * @return array{ok: bool, message: string, coupon: array|null, discount: int}
     */
    public static function validateForCart(string $code, array $cartItems, int $orderTotal): array
    {
        $code = trim($code);
        $coupon = self::findActiveByCode($code);

        if (!$coupon) {
            return ['ok' => false, 'message' => 'Mã giảm giá không tồn tại hoặc đã bị tắt.', 'coupon' => null, 'discount' => 0];
        }

        $now = time();
        if ($now < strtotime($coupon['start_date']) || $now > strtotime($coupon['end_date'])) {
            return ['ok' => false, 'message' => 'Mã giảm giá đã hết hạn hoặc chưa đến thời gian áp dụng.', 'coupon' => null, 'discount' => 0];
        }

        if ((int) $coupon['usage_limit'] > 0 && (int) $coupon['used_count'] >= (int) $coupon['usage_limit']) {
            return ['ok' => false, 'message' => 'Mã giảm giá đã hết lượt sử dụng.', 'coupon' => null, 'discount' => 0];
        }

        if ($orderTotal < (int) $coupon['min_order_value']) {
            return ['ok' => false, 'message' => 'Đơn hàng chưa đạt giá trị tối thiểu ' . number_format((int) $coupon['min_order_value']) . 'đ để áp dụng mã này.', 'coupon' => null, 'discount' => 0];
        }

        if ((int) $coupon['product_id'] > 0) {
            $hasProduct = false;
            foreach ($cartItems as $line) {
                if ((int) $line[0] === (int) $coupon['product_id']) {
                    $hasProduct = true;
                    break;
                }
            }
            if (!$hasProduct) {
                return ['ok' => false, 'message' => 'Mã giảm giá chỉ áp dụng cho một sản phẩm cụ thể không có trong giỏ hàng.', 'coupon' => null, 'discount' => 0];
            }
        }

        $discount = self::calculateDiscount($coupon, $orderTotal);

        return ['ok' => true, 'message' => 'Áp dụng mã giảm giá thành công!', 'coupon' => $coupon, 'discount' => $discount];
    }

    public static function calculateDiscount(array $coupon, int $orderTotal): int
    {
        if ((int) $coupon['discount_type'] === 1) {
            $discount = (int) round($orderTotal * (int) $coupon['discount_value'] / 100);
            if ((int) $coupon['max_discount'] > 0) {
                $discount = min($discount, (int) $coupon['max_discount']);
            }
        } else {
            $discount = (int) $coupon['discount_value'];
        }

        return min($discount, $orderTotal);
    }

    public static function incrementUsage(int $id_coupon): void
    {
        Database::execute("UPDATE coupons SET used_count = used_count + 1 WHERE id_coupon = ?", $id_coupon);
    }
}
