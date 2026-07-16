<?php

namespace App\Services;

use App\Models\Coupon;
use Carbon\Carbon;

/**
 * Ported from `Codemoi\Model\Coupon` (legacy `src/Model/Coupon.php`) — the
 * single source of truth for coupon rules, checked at both apply-time and
 * again right before an order is created so a stale/invalidated code can
 * never actually discount an order.
 */
class CouponService
{
    /**
     * @param  array<int, array{id_pro:int,quantity:int}>  $cartItems
     * @return array{ok:bool, message:string, coupon:?Coupon, discount:int}
     */
    public function validateForCart(string $code, array $cartItems, int $orderTotal): array
    {
        $code = trim($code);
        $coupon = Coupon::where('code', $code)->where('status', 1)->first();

        if (! $coupon) {
            return ['ok' => false, 'message' => 'Mã giảm giá không tồn tại hoặc đã bị tắt.', 'coupon' => null, 'discount' => 0];
        }

        $now = Carbon::now();
        if ($now->lt($coupon->start_date) || $now->gt($coupon->end_date)) {
            return ['ok' => false, 'message' => 'Mã giảm giá đã hết hạn hoặc chưa đến thời gian áp dụng.', 'coupon' => null, 'discount' => 0];
        }

        if ($coupon->usage_limit > 0 && $coupon->used_count >= $coupon->usage_limit) {
            return ['ok' => false, 'message' => 'Mã giảm giá đã hết lượt sử dụng.', 'coupon' => null, 'discount' => 0];
        }

        if ($orderTotal < $coupon->min_order_value) {
            return [
                'ok' => false,
                'message' => 'Đơn hàng chưa đạt giá trị tối thiểu '.number_format($coupon->min_order_value).'đ để áp dụng mã này.',
                'coupon' => null, 'discount' => 0,
            ];
        }

        if ($coupon->product_id > 0) {
            $hasProduct = collect($cartItems)->contains(fn ($line) => (int) $line['id_pro'] === (int) $coupon->product_id);
            if (! $hasProduct) {
                return ['ok' => false, 'message' => 'Mã giảm giá chỉ áp dụng cho một sản phẩm cụ thể không có trong giỏ hàng.', 'coupon' => null, 'discount' => 0];
            }
        }

        return [
            'ok' => true,
            'message' => 'Áp dụng mã giảm giá thành công!',
            'coupon' => $coupon,
            'discount' => $this->calculateDiscount($coupon, $orderTotal),
        ];
    }

    public function calculateDiscount(Coupon $coupon, int $orderTotal): int
    {
        if ((int) $coupon->discount_type === 1) {
            $discount = (int) round($orderTotal * $coupon->discount_value / 100);
            if ($coupon->max_discount > 0) {
                $discount = min($discount, $coupon->max_discount);
            }
        } else {
            $discount = $coupon->discount_value;
        }

        return min($discount, $orderTotal);
    }

    /**
     * Atomic — the `usage_limit` guard in the WHERE clause means this can
     * never push `used_count` past the cap even under concurrent checkouts.
     */
    public function incrementUsage(int $idCoupon): void
    {
        Coupon::where('id_coupon', $idCoupon)
            ->where(function ($q) {
                $q->where('usage_limit', 0)->orWhereColumn('used_count', '<', 'usage_limit');
            })
            ->increment('used_count');
    }
}
