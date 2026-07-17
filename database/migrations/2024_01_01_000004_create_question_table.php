<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('question', function (Blueprint $table) {
            $table->increments('id_ques');
            $table->string('name', 250);
            $table->string('email', 250);
            $table->string('phone', 20);
            $table->text('contennt');
            $table->text('reply')->nullable();
            $table->dateTime('replied_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question');
    }
};
