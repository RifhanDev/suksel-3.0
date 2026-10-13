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
            ->where('title', 'Senarai Kakitangan Teknikal')
            ->where('category', 'kewangan_kerja')
            ->where('type', 'borang_atas_talian')
            ->exists();

        if (!$exists) {
            DB::table('standard_checklist_items')->insert([
                'uuid'                  => (string) Str::uuid(),
                'category'              => 'kewangan_kerja',
                'type'                  => 'borang_atas_talian',
                'title'                 => 'Senarai Kakitangan Teknikal',
                'mechanism_default'     => null,
                'vendor_action_default' => null,
                'action_url'            => '/kakitangan-teknikal',
                'is_active'             => true,
                'sort_order'            => 6,
                'created_at'            => now(),
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
            ->where('title', 'Senarai Kakitangan Teknikal')
            ->where('category', 'kewangan_kerja')
            ->where('type', 'borang_atas_talian')
            ->delete();
    }
};
