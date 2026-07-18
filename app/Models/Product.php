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

    /** Latest 20 products for the home page. Mirrors `Product::allHome()`. */
    public function scopeHomeLatest($query)
    {
        return $query->orderByDesc('id_pro')->limit(20);
    }

    /** Top 8 by view count. Mirrors `Product::featured()`. */
    public function scopeFeatured($query)
    {
        return $query->orderByDesc('view')->limit(8);
    }

    /**
     * Best-selling products by units actually sold, cancelled orders
     * excluded — mirrors `Codemoi\Model\Product::bestSellers()`. Uses
     * SUM(quantity), not COUNT(*): a single 50-unit order should outrank
     * two separate 1-unit orders.
     */
    public static function bestSellers(int $limit = 8)
    {
        // Every selected product column must appear in GROUP BY —
        // MariaDB's ONLY_FULL_GROUP_BY here doesn't apply the
        // functional-dependency-on-primary-key exception the way MySQL
        // 5.7.5+ does for `product.*` + `GROUP BY product.id_pro` alone.
        return static::query()
            ->select([
                'product.id_pro', 'product.name_pro', 'product.price', 'product.discount',
                'product.img_pro', 'product.short_des', 'product.detail_des', 'product.view',
                'product.stock', 'product.stock_message', 'product.idcate',
            ])
            ->selectRaw('SUM(cart.quantity) as total_sale')
            ->join('cart', 'cart.id_pro', '=', 'product.id_pro')
            ->join('bill', 'bill.id_bill', '=', 'cart.id_bill')
            ->where('bill.status_pay', 1)
            ->where('bill.status', '!=', Order::STATUS_CANCELLED)
            ->groupBy([
                'product.id_pro', 'product.name_pro', 'product.price', 'product.discount',
                'product.img_pro', 'product.short_des', 'product.detail_des', 'product.view',
                'product.stock', 'product.stock_message', 'product.idcate',
            ])
            ->orderByDesc('total_sale')
            ->limit($limit)
            ->get();
    }

    /**
     * Keyword/category/price-range search. Mirrors `Product::search()` —
     * LIKE-escapes `\`, `%`, `_` in the keyword so a literal search for
     * e.g. "50%" searches for that literal substring, not a wildcard.
     */
    public static function search(string $keyword = '', int $idcate = 0, int $min = 0, int $max = 0)
    {
        $query = static::query();

        if ($keyword !== '') {
            $escaped = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $keyword);
            $query->where('name_pro', 'like', "%{$escaped}%");
        }
        if ($idcate > 0) {
            $query->where('idcate', $idcate);
        }
        if ($min > 0) {
            $query->where('price', '>=', $min);
        }
        if ($max > 0) {
            $query->where('price', '<=', $max);
        }

        return $query->orderByDesc('id_pro')->paginate(12);
    }

    /** Same category, excluding self. Mirrors `Product::similar()`. */
    public function similar(int $limit = 5)
    {
        return static::where('idcate', $this->idcate)
            ->where('id_pro', '!=', $this->id_pro)
            ->limit($limit)
            ->get();
    }
}
