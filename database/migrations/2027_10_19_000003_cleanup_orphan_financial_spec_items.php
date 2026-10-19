<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Remove kewangan "Mekanisma Spesifikasi" copies left after teknikal hapus.
 *
 * financial_checklist_items.technical_item_id uses ON DELETE SET NULL, so
 * deleting on senarai-teknikal orphans the kewangan row and it still shows on
 * senarai-kewangan-bekalan.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('financial_checklist_items')) {
            return;
        }

        $hasTechItems = Schema::hasTable('technical_checklist_items');

        if ($hasTechItems) {
            DB::statement(
                "DELETE fci
                   FROM financial_checklist_items fci
                  WHERE (fci.source_type = 'specification_document' OR fci.mechanism = 'spesifikasi')
                    AND (
                        fci.technical_item_id IS NULL
                        OR NOT EXISTS (
                            SELECT 1
                              FROM technical_checklist_items tci
                             WHERE tci.id = fci.technical_item_id
                        )
                    )"
            );

            return;
        }

        // Fallback if technical table missing: only clear NULL links.
        DB::table('financial_checklist_items')
            ->where(function ($q) {
                $q->where('source_type', 'specification_document')
                    ->orWhere('mechanism', 'spesifikasi');
            })
            ->whereNull('technical_item_id')
            ->delete();
    }

    public function down(): void
    {
        // Irreversible data cleanup — no restore.
    }
};
