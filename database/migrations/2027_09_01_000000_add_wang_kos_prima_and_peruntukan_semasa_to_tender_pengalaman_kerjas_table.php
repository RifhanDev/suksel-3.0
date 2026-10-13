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
        if (Schema::hasTable('tender_pengalaman_kerjas')) {
            Schema::table('tender_pengalaman_kerjas', function (Blueprint $table) {
                if (! Schema::hasColumn('tender_pengalaman_kerjas', 'wang_kos_prima')) {
                    $table->decimal('wang_kos_prima', 15, 2)->nullable()->after('telefon_pic');
                }
                if (! Schema::hasColumn('tender_pengalaman_kerjas', 'wang_peruntukan_semasa')) {
                    $table->decimal('wang_peruntukan_semasa', 15, 2)->nullable()->after('wang_kos_prima');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('tender_pengalaman_kerjas')) {
            Schema::table('tender_pengalaman_kerjas', function (Blueprint $table) {
                $table->dropColumn(['wang_kos_prima', 'wang_peruntukan_semasa']);
            });
        }
    }
};
