<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id_coupon');
            $table->string('code', 50)->unique();
            $table->tinyInteger('discount_type')->default(1);
            $table->integer('discount_value');
            $table->integer('max_discount')->default(0);
            $table->integer('min_order_value')->default(0);
            $table->integer('product_id')->default(0);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('usage_limit')->default(0);
            $table->integer('used_count')->default(0);
            $table->tinyInteger('status')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
