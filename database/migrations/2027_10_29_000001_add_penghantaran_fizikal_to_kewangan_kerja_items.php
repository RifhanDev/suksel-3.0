<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Both applications may use the same database.
        if (! Schema::hasColumn('kewangan_kerja_items', 'penghantaran_fizikal')) {
                        if (Schema::hasTable('kewangan_kerja_items')) {
Schema::table('kewangan_kerja_items', function (Blueprint $table) {
                if (! Schema::hasColumn('kewangan_kerja_items', 'penghantaran_fizikal')) {
                $table->boolean('penghantaran_fizikal')->default(false);
                }
            });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('kewangan_kerja_items', 'penghantaran_fizikal')) {
            Schema::table('kewangan_kerja_items', function (Blueprint $table) {
                $table->dropColumn('penghantaran_fizikal');
            });
        }
    }
};