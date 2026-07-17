<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user', function (Blueprint $table) {
            $table->increments('id_user');
            $table->string('user_name', 50)->unique('idx_user_name');
            $table->string('password');
            $table->string('full_name');
            $table->tinyInteger('sex')->default(0);
            $table->string('email_user')->unique('idx_email_user');
            $table->string('address');
            $table->string('phone_user', 25);
            $table->string('img_user');
            $table->date('register_date')->nullable();
            $table->date('last_login')->nullable();
            $table->tinyInteger('role')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
