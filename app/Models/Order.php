<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Maps onto the existing `bill` table (order header) — the domain naming
 * caveat from the legacy code carries over: `cart` (mapped by OrderItem)
 * holds persisted order line items, not a live shopping cart (that stays
 * session-based, untouched by this migration).
 *
 * Status: 0=Đơn hàng mới, 1=Đang xử lí, 2=Đang giao hàng, 3=Đã giao hàng,
 * 4=Đã hủy. The full status-transition state machine and the
 * restock/coupon-refund-on-cancel logic live in a dedicated service
 * (Phase 4 Group E), not here — kept off the model so it can't be
 * bypassed by calling `save()` directly from a different entry point.
 */
class Order extends Model
{
    protected $table = 'bill';

    protected $primaryKey = 'id_bill';

    public $timestamps = false;

    protected $fillable = [
        'bill_code',
        'id_pro',
        'name_pro',
        'id_user',
        'user_name',
        'full_name',
        'address',
        'phone',
        'email',
        'payment',
        'order_date',
        'total_amount',
        'status',
        'status_pay',
        'coupon_code',
        'id_coupon',
        'discount_amount',
    ];

    protected function casts(): array
    {
        return [
            'order_date' => 'datetime',
            'total_amount' => 'integer',
            'status' => 'integer',
            'discount_amount' => 'integer',
        ];
    }

    public const STATUS_NEW = 0;

    public const STATUS_PROCESSING = 1;

    public const STATUS_SHIPPING = 2;

    public const STATUS_DELIVERED = 3;

    public const STATUS_CANCELLED = 4;

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'id_bill', 'id_bill');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'id_coupon', 'id_coupon');
    }

    /**
     * Excludes cancelled orders — required on every revenue/bestseller
     * query. Cancelling an order never resets `status_pay`, so a
     * bank-transfer order marked paid before it was cancelled would
     * otherwise permanently inflate every dashboard figure.
     */
    public function scopeNotCancelled($query)
    {
        return $query->where('status', '!=', self::STATUS_CANCELLED);
    }
}
