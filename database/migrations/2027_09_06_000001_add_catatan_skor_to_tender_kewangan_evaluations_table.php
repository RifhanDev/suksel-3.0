<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tender_kewangan_evaluations') && !Schema::hasColumn('tender_kewangan_evaluations', 'catatan_skor')) {
            Schema::table('tender_kewangan_evaluations', function (Blueprint $table) {
                $table->text('catatan_skor')->nullable()->after('catatan');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tender_kewangan_evaluations') && Schema::hasColumn('tender_kewangan_evaluations', 'catatan_skor')) {
            Schema::table('tender_kewangan_evaluations', function (Blueprint $table) {
                $table->dropColumn('catatan_skor');
            });
        }
    }
};
