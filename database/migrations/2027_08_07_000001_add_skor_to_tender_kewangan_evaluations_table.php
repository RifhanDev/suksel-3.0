<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tender_kewangan_evaluations') && !Schema::hasColumn('tender_kewangan_evaluations', 'skor')) {
            Schema::table('tender_kewangan_evaluations', function (Blueprint $table) {
                $table->decimal('skor', 8, 2)->nullable()->default(0.00)->after('catatan');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tender_kewangan_evaluations') && Schema::hasColumn('tender_kewangan_evaluations', 'skor')) {
            Schema::table('tender_kewangan_evaluations', function (Blueprint $table) {
                $table->dropColumn('skor');
            });
        }
    }
};
