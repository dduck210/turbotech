<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Maps onto the existing `cart` table — despite the name, this holds
 * PERSISTED ORDER LINE ITEMS written at checkout (`id_bill` FK), not the
 * live shopping cart (session-based, has no DB table at all). Primary key
 * is the one table in this schema actually called `id`.
 */
class OrderItem extends Model
{
    protected $table = 'cart';

    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'user_name',
        'id_pro',
        'img_pro',
        'name_pro',
        'price_pro',
        'quantity',
        'total_amount',
        'id_bill',
    ];

    protected function casts(): array
    {
        return [
            'price_pro' => 'integer',
            'quantity' => 'integer',
            'total_amount' => 'integer',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'id_bill', 'id_bill');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_pro', 'id_pro');
    }
}