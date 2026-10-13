<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $exists = DB::table('standard_checklist_items')
            ->where('title', 'Spesifikasi Komponen Mekanikal/Elektrikal')
            ->where('category', 'kewangan_kerja')
            ->exists();

        if (!$exists) {
            DB::table('standard_checklist_items')->insert([
                'uuid'                  => (string) Str::uuid(),
                'category'              => 'kewangan_kerja',
                'type'                  => 'standard',
                'title'                 => 'Spesifikasi Komponen Mekanikal/Elektrikal',
                'mechanism_default'     => 'ptj_muat_naik',
                'vendor_action_default' => 'muat_turun_naik',
                'action_url'            => null,
                'is_active'             => true,
                'sort_order'            => 9,
                'created_at'            => now(),
                'updated_at'            => now(),
            ]);
        } else {
            DB::table('standard_checklist_items')
                ->where('title', 'Spesifikasi Komponen Mekanikal/Elektrikal')
                ->where('category', 'kewangan_kerja')
                ->update([
                    'type'                  => 'standard',
                    'mechanism_default'     => 'ptj_muat_naik',
                    'vendor_action_default' => 'muat_turun_naik',
                    'updated_at'            => now(),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('standard_checklist_items')
            ->where('title', 'Spesifikasi Komponen Mekanikal/Elektrikal')
            ->where('category', 'kewangan_kerja')
            ->delete();
    }
};
