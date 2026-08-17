<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tender_kewangan_progress', function (Blueprint $table) {
            if (! Schema::hasColumn('tender_kewangan_progress', 'borang_status')) {
                $table->json('borang_status')->nullable()->after('current_step')->comment('Tracks completion status of Borang 1 to 15 for Kerja');
            }
            if (! Schema::hasColumn('tender_kewangan_progress', 'peringkat1_confirmed_at')) {
                $table->timestamp('peringkat1_confirmed_at')->nullable()->after('step3_confirmed_by');
                $table->unsignedBigInteger('peringkat1_confirmed_by')->nullable()->after('peringkat1_confirmed_at');
            }
            if (! Schema::hasColumn('tender_kewangan_progress', 'peringkat2_confirmed_at')) {
                $table->timestamp('peringkat2_confirmed_at')->nullable()->after('peringkat1_confirmed_by');
                $table->unsignedBigInteger('peringkat2_confirmed_by')->nullable()->after('peringkat2_confirmed_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tender_kewangan_progress', function (Blueprint $table) {
            $table->dropColumn([
                'borang_status',
                'peringkat1_confirmed_at',
                'peringkat1_confirmed_by',
                'peringkat2_confirmed_at',
                'peringkat2_confirmed_by',
            ]);
        });
    }
};
