<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('specification_pricing_items', function (Blueprint $table) {
            $table->unsignedBigInteger('spec_detail_id')->nullable()->after('spec_item_id');
        });
    }

    public function down(): void
    {
        Schema::table('specification_pricing_items', function (Blueprint $table) {
            $table->dropColumn('spec_detail_id');
        });
    }
};
