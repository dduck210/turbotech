<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * `bill` = order header (Order model). `id_user` has an index but no FK
 * constraint, `phone` is genuinely an INT column (not varchar, unlike
 * every other phone column in this schema) — both preserved exactly as
 * the existing table has them, not "corrected" here.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bill', function (Blueprint $table) {
            $table->increments('id_bill');
            $table->string('bill_code')->nullable();
            $table->integer('id_user');
            $table->integer('id_pro');
            $table->string('user_name');
            $table->string('name_pro');
            $table->string('full_name', 55);
            $table->string('address');
            $table->integer('phone');
            $table->string('email');
            // 1=COD, 2=bank transfer, 3=online payment
            $table->tinyInteger('payment')->default(1);
            $table->dateTime('order_date');
            $table->integer('total_amount');
            // 0=new, 1=processing, 2=shipping, 3=delivered, 4=cancelled
            $table->tinyInteger('status');
            $table->string('status_pay', 11)->default('0');
            $table->string('coupon_code', 50)->nullable();
            $table->integer('id_coupon')->nullable();
            $table->integer('discount_amount')->default(0);

            $table->index('id_user', 'lk_user_bill');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bill');
    }
};
