<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Leftover from the legacy schema — always 0 rows, no model or code
 * anywhere references it. Bank-transfer details now live in
 * config('services.vietqr') via BANK_* env vars (VietQrService), not
 * a database table.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('bank');
    }

    public function down(): void
    {
        Schema::create('bank', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name_bank');
            $table->string('num_bank');
            $table->string('name_num');
        });
    }
};
