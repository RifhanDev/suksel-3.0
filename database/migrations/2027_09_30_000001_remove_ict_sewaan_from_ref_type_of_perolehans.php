<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('ref_type_of_perolehans')
            ->whereIn('ref_kategori_jenis_perolehan_id', [1, 2])
            ->whereIn('name', ['ICT', 'Sewaan'])
            ->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ([1, 2] as $kategoriId) {
            foreach (['ICT', 'Sewaan'] as $name) {
                DB::table('ref_type_of_perolehans')->insertOrIgnore([
                    'ref_kategori_jenis_perolehan_id' => $kategoriId,
                    'name' => $name,
                    'active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
};
