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
 *
 * --- Kategori Jenis Perolehan block below ---
 * Same reconcile approach, added as its own independent block (not sharing
 * code with the kaedah block above, which is proven working on staging and
 * is left untouched) for ref_kategori_jenis_perolehans -> "Perkhidmatan",
 * "Bekalan", "Kerja" (the values KategoriJenisPerolehan.php seeds, and that
 * TypeOfPerolehan.php already depends on by hardcoded id 1/2/3).
 *
 * FK behaviour is DIFFERENT here and matters for what a delete actually
 * does downstream: tenders.kategori_perolehan_id -> this table is
 * ->onDelete('set null') like kaedah, but
 * ref_type_of_perolehans.ref_kategori_jenis_perolehan_id -> this table is
 * ->onDelete('cascade') (2027_01_08_014319_add_ref_kategori_jenis_perolehan_id_to_ref_type_of_perolehans_table.php)
 * — deleting a kategori row here CASCADE-DELETES any ref_type_of_perolehans
 * rows still pointing at it. Today this is a no-op (the three desired names
 * already exist, so the delete step matches nothing).
 *
 * --- Sub-kategori (ref_type_of_perolehans) seed, run last ---
 * Runs Database\Seeders\Ref\TypeOfPerolehan after the two blocks above have
 * guaranteed ref_kategori_jenis_perolehans has exactly Perkhidmatan=1,
 * Bekalan=2, Kerja=3 — the ids that seeder's own rows hardcode
 * (ref_kategori_jenis_perolehan_id => 1/2/3), so ordering matters here.
 *
 * Guarded by an emptiness check on ref_type_of_perolehans: that seeder is
 * plain RefTypeOfPerolehan::create() calls with no existence check of its
 * own, so calling it unconditionally would duplicate its 17 rows every time
 * this migration is cleared from the migrations table and re-run (which is
 * exactly how this migration has been tested/iterated on so far). Skipped
 * entirely once the table has any rows at all.
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

        // --- Kategori Jenis Perolehan (separate block, same concept) ---
        $desiredKategori = ['Perkhidmatan', 'Bekalan', 'Kerja'];

        // Remove only what's NOT in the desired list.
        DB::table('ref_kategori_jenis_perolehans')
            ->whereNotIn('name', $desiredKategori)
            ->delete();

        // If the table is now completely empty, force its auto-increment
        // counter back to 1 before inserting. Guarantees Perkhidmatan/
        // Bekalan/Kerja land as id 1/2/3 exactly — required by the
        // TypeOfPerolehan seed below — regardless of this table's insert
        // history on this environment (a rolled-back transaction does NOT
        // roll back auto-increment, so "the table is empty" alone doesn't
        // guarantee the next ids will be 1/2/3 without this). Safe only
        // because it's gated on the table having zero rows right now.
        if (DB::table('ref_kategori_jenis_perolehans')->count() === 0) {
            DB::statement('ALTER TABLE ref_kategori_jenis_perolehans AUTO_INCREMENT = 1');
        }

        // Insert only whichever desired names don't already exist — leaves
        // existing matching rows (and their ids) completely alone.
        $existingKategori = DB::table('ref_kategori_jenis_perolehans')
            ->whereIn('name', $desiredKategori)
            ->pluck('name')
            ->all();

        foreach ($desiredKategori as $name) {
            if (in_array($name, $existingKategori, true)) {
                continue;
            }

            DB::table('ref_kategori_jenis_perolehans')->insert([
                'name'        => $name,
                'description' => null,
                'active'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        // --- Sub-kategori seed (see docblock) — only if the table is empty ---
        if (DB::table('ref_type_of_perolehans')->count() === 0) {
            (new \Database\Seeders\Ref\TypeOfPerolehan())->run();
        }
    }

    public function down(): void
    {
        // Deliberately not implemented: the pre-migration state was drifted/
        // inconsistent data (a manual insert plus a stray seeder run), not a
        // state worth restoring.
    }
};
