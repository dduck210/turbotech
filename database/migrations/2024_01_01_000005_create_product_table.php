<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product', function (Blueprint $table) {
            $table->increments('id_pro');
            $table->string('name_pro');
            $table->integer('price');
            $table->integer('discount');
            $table->string('img_pro');
            $table->text('short_des');
            $table->text('detail_des');
            $table->integer('view')->default(0);
            $table->integer('stock')->default(20);
            $table->string('stock_message')->nullable();
            $table->unsignedInteger('idcate');

            $table->foreign('idcate', 'lk_cate_product')
                ->references('id_cate')->on('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
