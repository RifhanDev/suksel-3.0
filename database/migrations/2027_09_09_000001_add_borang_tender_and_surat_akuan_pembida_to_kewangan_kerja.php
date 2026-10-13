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
        $newItems = [
            [
                'title'                 => 'Borang Tender',
                'action_url'            => null,
                'mechanism_default'     => 'ptj_muat_naik',
                'vendor_action_default' => 'muat_turun_naik',
                'sort_order'            => 7,
            ],
            [
                'title'                 => 'Surat Akuan Pembida',
                'action_url'            => null,
                'mechanism_default'     => 'ptj_muat_naik',
                'vendor_action_default' => 'muat_turun_naik',
                'sort_order'            => 8,
            ],
        ];

        foreach ($newItems as $item) {
            $exists = DB::table('standard_checklist_items')
                ->where('title', $item['title'])
                ->where('category', 'kewangan_kerja')
                ->where('type', 'borang_atas_talian')
                ->exists();

            if (!$exists) {
                DB::table('standard_checklist_items')->insert([
                    'uuid'                  => (string) Str::uuid(),
                    'category'              => 'kewangan_kerja',
                    'type'                  => 'borang_atas_talian',
                    'title'                 => $item['title'],
                    'mechanism_default'     => $item['mechanism_default'],
                    'vendor_action_default' => $item['vendor_action_default'],
                    'action_url'            => $item['action_url'],
                    'is_active'             => true,
                    'sort_order'            => $item['sort_order'],
                    'created_at'            => now(),
                    'updated_at'            => now(),
                ]);
            } else {
                DB::table('standard_checklist_items')
                    ->where('title', $item['title'])
                    ->where('category', 'kewangan_kerja')
                    ->where('type', 'borang_atas_talian')
                    ->update([
                        'mechanism_default'     => $item['mechanism_default'],
                        'vendor_action_default' => $item['vendor_action_default'],
                        'updated_at'            => now(),
                    ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('standard_checklist_items')
            ->whereIn('title', ['Borang Tender', 'Surat Akuan Pembida'])
            ->where('category', 'kewangan_kerja')
            ->where('type', 'borang_atas_talian')
            ->delete();
    }
};
