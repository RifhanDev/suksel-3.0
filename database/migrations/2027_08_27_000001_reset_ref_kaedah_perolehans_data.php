<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Resets ref_kaedah_perolehans to exactly the four values confirmed as the
 * real "Kaedah Perolehan" list (per the actual dropdown on /cipta-tender):
 * "Tender", "Sebut Harga", "Pembelian Terus", "Lantikan Terus".
 *
 * Context: this table had drifted — "Pembelian Terus"/"Lantikan Terus" were
 * inserted directly via Tinker on 2026-08-11 (bypassing KaedahPerolehanSeeder
 * entirely), and at some point KaedahPerolehanSeeder also got run on top of
 * that (adding "Tender"/"Sebut Harga"), leaving all four mixed together with
 * no single source of truth. This migration makes the four-value list
 * explicit and guaranteed, on any environment it runs on — it does not
 * remove any of the four.
 *
 * Deliberately NOT a blind delete-all-then-reinsert: a row already matching
 * one of the desired names is left completely untouched (same id, same
 * created_at), so any tender already referencing one of the four keeps its
 * kaedah_perolehan_id intact. Only a row whose name is NOT in this list
 * would be deleted — today that's a no-op since all four already exist, but
 * it keeps this migration correct if it's ever re-run after further drift.
 *
 * Safe against tenders.kaedah_perolehan_id for any row that WOULD be
 * deleted: that FK is declared ->onDelete('set null') (see
 * 2027_01_08_074426_add_new_fields_to_tenders_table.php), so a delete here
 * can't be blocked by, or cascade-delete, a tender referencing it — it only
 * nulls that column on the referencing row.
 */
return new class extends Migration
{
    public function up(): void
    {
        $desired = ['Tender', 'Sebut Harga', 'Pembelian Terus', 'Lantikan Terus'];

        // Remove only what's NOT in the desired list.
        DB::table('ref_kaedah_perolehans')
            ->whereNotIn('name', $desired)
            ->delete();

        // Insert only whichever desired names don't already exist — leaves
        // existing matching rows (and their ids) completely alone.
        $existing = DB::table('ref_kaedah_perolehans')
            ->whereIn('name', $desired)
            ->pluck('name')
            ->all();

        foreach ($desired as $name) {
            if (in_array($name, $existing, true)) {
                continue;
            }

            DB::table('ref_kaedah_perolehans')->insert([
                'name'        => $name,
                'description' => null,
                'active'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }

    public function down(): void
    {
        // Deliberately not implemented: the pre-migration state was drifted/
        // inconsistent data (a manual insert plus a stray seeder run), not a
        // state worth restoring.
    }
};
