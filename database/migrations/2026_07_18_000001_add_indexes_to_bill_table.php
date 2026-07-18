<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * `status` and `order_date` are filtered/sorted on by the admin order
 * list, the stats revenue query, and the dashboard's recent-orders
 * widget — none had a supporting index before this, so each of those
 * queries (and pagination's extra COUNT query) ran a full table scan.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bill', function (Blueprint $table) {
            $table->index('status', 'idx_bill_status');
            $table->index('order_date', 'idx_bill_order_date');
        });
    }

    public function down(): void
    {
        Schema::table('bill', function (Blueprint $table) {
            $table->dropIndex('idx_bill_status');
            $table->dropIndex('idx_bill_order_date');
        });
    }
};
