<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * `cart` = persisted order line items (OrderItem model), written at
 * checkout — not the live shopping cart, which is session-based and has
 * no table at all. The only table in this schema with a plain `id` PK.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user');
            $table->string('user_name', 50);
            $table->unsignedInteger('id_pro');
            $table->string('img_pro');
            $table->string('name_pro');
            $table->integer('price_pro');
            $table->integer('quantity');
            $table->integer('total_amount');
            $table->unsignedInteger('id_bill');

            // foreign() below creates its own backing index per column
            // (matching how MySQL auto-names a FK's supporting index after
            // the constraint) — a separate index() with the same name
            // would collide with it.
            $table->index(['id_user', 'id_pro'], 'lk_user_cart');
            $table->foreign('id_bill', 'lk_bill_cart')
                ->references('id_bill')->on('bill');
            $table->foreign('id_pro', 'lk_pro_cart')
                ->references('id_pro')->on('product');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart');
    }
};
