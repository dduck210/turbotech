<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';

    protected $primaryKey = 'id_pro';

    public $timestamps = false;

    protected $fillable = [
        'name_pro',
        'price',
        'discount',
        'img_pro',
        'short_des',
        'detail_des',
        'view',
        'stock',
        'stock_message',
        'idcate',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'discount' => 'integer',
            'view' => 'integer',
            'stock' => 'integer',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'idcate', 'id_cate');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'id_pro', 'id_pro');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'id_pro', 'id_pro');
    }

    /**
     * Post-discount unit price — the same "sale price" every
     * customer-facing template shows. Ported verbatim from
     * `Codemoi\Model\Product::discountedPrice()` (legacy `src/Model/Product.php`)
     * so the checkout charge and every displayed price stay in sync
     * (previously drifted apart once, undercharging every discounted
     * product — see that method's docblock in the legacy code for the
     * incident this accessor exists to prevent from recurring).
     */
    public function getDiscountedPriceAttribute(): int
    {
        if ($this->discount <= 0) {
            return (int) $this->price;
        }

        return (int) round($this->price - ($this->price * $this->discount / 100));
    }

    public function hasStock(int $quantity = 1): bool
    {
        return $this->stock >= $quantity;
    }
}
