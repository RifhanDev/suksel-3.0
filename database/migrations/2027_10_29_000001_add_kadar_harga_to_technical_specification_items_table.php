<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('technical_specification_items', function (Blueprint $table) {
            if (! Schema::hasColumn('technical_specification_items', 'kadar_harga')) {
                $table->decimal('kadar_harga', 15, 2)->nullable()->after('unit');
            }
        });
    }

    public function down(): void
    {
        Schema::table('technical_specification_items', function (Blueprint $table) {
            if (Schema::hasColumn('technical_specification_items', 'kadar_harga')) {
                $table->dropColumn('kadar_harga');
            }
        });
    }
};
