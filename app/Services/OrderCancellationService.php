<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;

/**
 * Ported from `Codemoi\Model\Order::cancel()`/`restockAndRefundCoupon()`
 * (legacy `src/Model/Order.php`). Centralized here (not on the Order model
 * or scattered across controllers) so every path that can cancel an order
 * — client self-cancel (`AccountController`), admin quick-cancel and the
 * manual status-edit form (Phase 4 Group E) — shares exactly one
 * "restore stock + coupon usage exactly once" implementation. Callers are
 * responsible for their own authorization/current-state checks before
 * calling `cancel()` — this service just performs the cancellation once
 * the caller has decided it's allowed.
 */
class OrderCancellationService
{
    public function cancel(Order $order): void
    {
        if ((int) $order->status === Order::STATUS_CANCELLED) {
            return;
        }

        $order->update(['status' => Order::STATUS_CANCELLED]);

        foreach ($order->items as $line) {
            Product::where('id_pro', $line->id_pro)->increment('stock', $line->quantity);
        }

        if ($order->id_coupon) {
            Coupon::where('id_coupon', $order->id_coupon)
                ->where('used_count', '>', 0)
                ->decrement('used_count');
        } elseif ($order->coupon_code) {
            $coupon = Coupon::where('code', $order->coupon_code)->first();
            if ($coupon && $coupon->used_count > 0) {
                $coupon->decrement('used_count');
            }
        }
    }
}
