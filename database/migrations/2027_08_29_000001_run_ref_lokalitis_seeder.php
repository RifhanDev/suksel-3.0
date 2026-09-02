<?php

use Database\Seeders\RefLokalitisSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Mengisi `ref_lokalitis` sebagai sebahagian daripada setiap deploy, supaya
 * dropdown "Lokaliti Liputan" pada borang cipta tender tidak kosong.
 *
 * Jadual ini baharu dalam 3.0 dan tiada dalam dump produksi 2.0, jadi
 * migration mencipta ia kosong dan ia kekal kosong sehingga seeder dijalankan.
 * Itulah yang berlaku di staging: borang memaparkan hanya "Pilih...".
 *
 * Tiada data didua di sini — senarai dan sebab ia selamat dijalankan berulang
 * kali ada dalam docblock RefLokalitisSeeder.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ref_lokalitis')) {
            return;
        }

        (new RefLokalitisSeeder())->run();
    }

    public function down(): void
    {
        if (! Schema::hasTable('ref_lokalitis')) {
            return;
        }

        $names = array_column(RefLokalitisSeeder::LOKALITIS, 'name');

        $query = DB::table('ref_lokalitis')->whereIn('name', $names);

        // Jangan buang baris yang sedang dirujuk. Meninggalkan baris yatim
        // lebih baik daripada membiarkan tenders.lokaliti_id menuding ke baris
        // yang sudah tiada — rollback tidak sepatutnya merosakkan data tender.
        if (Schema::hasColumn('tenders', 'lokaliti_id')) {
            $query->whereNotIn('id', function ($sub) {
                $sub->select('lokaliti_id')
                    ->from('tenders')
                    ->whereNotNull('lokaliti_id');
            });
        }

        $query->delete();
    }
};
