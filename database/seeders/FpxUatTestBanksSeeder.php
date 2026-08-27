<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeds fpx_banks rows for PayNet's UAT simulator banks (TEST0021, TEST0022,
 * TEST0023) with a recognizable display_name, so FpxController::bankList()
 * shows them properly labeled instead of falling back to a bare status
 * character that made them impossible to find in the "Pilih Bank Anda"
 * dropdown alongside 25+ real bank names.
 *
 * Deliberately a SEEDER, not a migration: these codes only ever appear in
 * PayNet's UAT RetrieveBankList response, never production's — a migration
 * would insert them on every environment migrate touches, including
 * production, where they'd sit as permanently-unused rows. Run this
 * explicitly, only on UAT/staging:
 *   php artisan db:seed --class=FpxUatTestBanksSeeder
 *
 * Idempotent: skips any code that already has a row.
 */
class FpxUatTestBanksSeeder extends Seeder
{
    public function run(): void
    {
        $banks = [
            ['code' => 'TEST0021', 'name' => 'TEST0021', 'display_name' => 'Bank Ujian PayNet A (UAT)'],
            ['code' => 'TEST0022', 'name' => 'TEST0022', 'display_name' => 'Bank Ujian PayNet B (UAT)'],
            ['code' => 'TEST0023', 'name' => 'TEST0023', 'display_name' => 'Bank Ujian PayNet C (UAT)'],
        ];

        $existing = DB::table('fpx_banks')
            ->whereIn('code', array_column($banks, 'code'))
            ->pluck('code')
            ->all();

        foreach ($banks as $bank) {
            if (in_array($bank['code'], $existing, true)) {
                $this->command?->info("FpxUatTestBanksSeeder: {$bank['code']} sudah wujud, tiada tindakan.");
                continue;
            }

            DB::table('fpx_banks')->insert([
                'code'         => $bank['code'],
                'name'         => $bank['name'],
                'display_name' => $bank['display_name'],
                'type'         => 'fpx',
                'status'       => 'active',
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            $this->command?->info("FpxUatTestBanksSeeder: {$bank['code']} ({$bank['display_name']}) dicipta.");
        }
    }
}
