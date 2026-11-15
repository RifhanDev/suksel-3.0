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
                if (Schema::hasTable('tender_prestasi_kerja_items')) {
Schema::table('tender_prestasi_kerja_items', function (Blueprint $table) {
            if (! Schema::hasColumn('tender_prestasi_kerja_items', 'jenis')) {
            $table->tinyInteger('jenis')->nullable()->comment('1: Serupa, 2: Sebanding')->after('sort_order');
            }
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tender_prestasi_kerja_items', function (Blueprint $table) {
            $table->dropColumn('jenis');
        });
    }
};
