<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ebidding_vendor_bid_items')) {
            return;
        }

        if (Schema::hasColumn('ebidding_vendor_bid_items', 'is_carried_forward')) {
            return;
        }

        Schema::table('ebidding_vendor_bid_items', function (Blueprint $table) {
            $table->boolean('is_carried_forward')->default(false)->after('bid_price');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('ebidding_vendor_bid_items')) {
            return;
        }

        if (! Schema::hasColumn('ebidding_vendor_bid_items', 'is_carried_forward')) {
            return;
        }

        Schema::table('ebidding_vendor_bid_items', function (Blueprint $table) {
            $table->dropColumn('is_carried_forward');
        });
    }
};
