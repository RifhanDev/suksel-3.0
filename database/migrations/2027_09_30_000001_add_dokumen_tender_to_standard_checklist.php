<?php

use Database\Seeders\StandardChecklistItemSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Adds the standard tender documents to Senarai Semak Standard
 * (technical, financial, and kewangan_kerja).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('standard_checklist_items')) {
            return;
        }

        (new StandardChecklistItemSeeder())->run();
    }

    public function down(): void
    {
        if (! Schema::hasTable('standard_checklist_items')) {
            return;
        }

        DB::table('standard_checklist_items')
            ->where('type', 'standard')
            ->whereIn('category', ['technical', 'financial', 'kewangan_kerja'])
            ->whereIn('title', [
                'Borang Tender / Borang Sebut Harga / Lampiran Q / Senarai Kuantiti BQ',
                'Borang A - Borang pengakuan kebenaran maklumat dan kesahihan dokumen yang dikemukakan oleh pembida',
                'Surat Akuan Pembida',
                'Borang Pemberitahuan pemunya benefisial (Borang B.O.)',
                'Surat akuan syarikat dalam menangani jenayah pemerdagangan orang dan buruh paksa. (Surat S.A.)',
                'Sijil Pematuhan Cukai (Tax Compliance Certificate – TCC)',
            ])
            ->delete();
    }
};
