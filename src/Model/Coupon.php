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

    /**
     * The `usage_limit` guard makes this atomic at the DB level — without
     * it, two checkouts racing past `validateForCart()`'s (separate,
     * earlier) limit check at the same instant could both increment past
     * the cap. `usage_limit = 0` means unlimited, matching `validateForCart()`.
     */
    public static function incrementUsage(int $id_coupon): void
    {
        Database::execute(
            "UPDATE coupons SET used_count = used_count + 1 WHERE id_coupon = ? AND (usage_limit = 0 OR used_count < usage_limit)",
            $id_coupon
        );
    }

    /**
     * All coupons, newest first. Mirrors old
     * `admin/model/coupon.php::loadall_coupon()`.
     */
    public static function allAdmin(): array
    {
        return Database::query("SELECT * FROM coupons ORDER BY id_coupon DESC");
    }

    /**
     * Look up a single coupon by id. Mirrors old `loadone_coupon($id_coupon)`.
     *
     * @return array|false
     */
    public static function find(int $idCoupon)
    {
        return Database::queryOne("SELECT * FROM coupons WHERE id_coupon = ?", $idCoupon);
    }

    /** Mirrors old `insert_coupon(...)`. */
    public static function create(
        string $code,
        int $discountType,
        float $discountValue,
        float $maxDiscount,
        float $minOrderValue,
        int $productId,
        string $startDate,
        string $endDate,
        int $usageLimit,
        int $status
    ): void {
        $sql = "INSERT INTO coupons(code, discount_type, discount_value, max_discount, min_order_value, product_id, start_date, end_date, usage_limit, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        Database::execute($sql, $code, $discountType, $discountValue, $maxDiscount, $minOrderValue, $productId, $startDate, $endDate, $usageLimit, $status);
    }

    /** Mirrors old `update_coupon(...)`. */
    public static function update(
        int $idCoupon,
        string $code,
        int $discountType,
        float $discountValue,
        float $maxDiscount,
        float $minOrderValue,
        int $productId,
        string $startDate,
        string $endDate,
        int $usageLimit,
        int $status
    ): void {
        $sql = "UPDATE coupons SET code = ?, discount_type = ?, discount_value = ?, max_discount = ?, min_order_value = ?, product_id = ?, start_date = ?, end_date = ?, usage_limit = ?, status = ? WHERE id_coupon = ?";
        Database::execute($sql, $code, $discountType, $discountValue, $maxDiscount, $minOrderValue, $productId, $startDate, $endDate, $usageLimit, $status, $idCoupon);
    }

    /** Mirrors old `admin/model/coupon.php::delete_coupon($id_coupon)`. */
    public static function delete(int $idCoupon): void
    {
        Database::execute("DELETE FROM coupons WHERE id_coupon = ?", $idCoupon);
    }
}
