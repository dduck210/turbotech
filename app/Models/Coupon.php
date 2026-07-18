<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Discount codes. Validation (date range, usage limit, min order value,
 * single-product restriction) and redemption/refund logic live in
 * CouponService (Phase 4 Group C), not here, so checkout and cancellation
 * can share exactly one implementation of "is this code usable right now."
 */
class Coupon extends Model
{
    protected $table = 'coupons';

    protected $primaryKey = 'id_coupon';

    public $timestamps = false;

    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'max_discount',
        'min_order_value',
        'product_id',
        'start_date',
        'end_date',
        'usage_limit',
        'used_count',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'discount_type' => 'integer',
            'discount_value' => 'integer',
            'max_discount' => 'integer',
            'min_order_value' => 'integer',
            'product_id' => 'integer',
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'usage_limit' => 'integer',
            'used_count' => 'integer',
            'status' => 'integer',
        ];
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'id_coupon', 'id_coupon');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}