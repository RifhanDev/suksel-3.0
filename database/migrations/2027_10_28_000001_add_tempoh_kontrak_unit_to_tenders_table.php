<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tenders')) {
            return;
        }

        if (! Schema::hasColumn('tenders', 'tempoh_kontrak_unit')) {
            Schema::table('tenders', function (Blueprint $table) {
                $table->string('tempoh_kontrak_unit', 20)->nullable()->after('tempoh_kontrak_bulan');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('tenders') || ! Schema::hasColumn('tenders', 'tempoh_kontrak_unit')) {
            return;
        }

        Schema::table('tenders', function (Blueprint $table) {
            $table->dropColumn('tempoh_kontrak_unit');
        });
    }
};
