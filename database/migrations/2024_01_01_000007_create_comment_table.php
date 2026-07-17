<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comment', function (Blueprint $table) {
            $table->increments('id_cmt');
            $table->string('content');
            $table->integer('id_user');
            $table->string('user_name', 50);
            $table->string('full_name');
            $table->unsignedInteger('id_pro');
            $table->string('comment_date', 30);

            $table->unique(['id_user', 'id_pro'], 'idx_user_product_review');
            $table->index('id_user', 'lk_user_cmt');
            $table->foreign('id_pro', 'lk_pro_cmt')
                ->references('id_pro')->on('product');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comment');
    }
};
