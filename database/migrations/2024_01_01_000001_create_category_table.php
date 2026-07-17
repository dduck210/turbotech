<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Describes the schema this project has always run on (originally created
 * outside Laravel's migration system) so a fresh environment can run
 * `php artisan migrate` instead of hand-importing Turbotech.sql. Not
 * applied against the live database — see the other 7 migrations in this
 * batch for the full picture, and app/Models/*.php for the Eloquent side
 * of each table (including the `$table`/`$primaryKey` overrides, since
 * none of these follow Laravel's naming conventions).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category', function (Blueprint $table) {
            $table->increments('id_cate');
            $table->string('name_cate');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category');
    }
};
