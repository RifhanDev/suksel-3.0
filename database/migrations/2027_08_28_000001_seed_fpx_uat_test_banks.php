<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Seeds fpx_banks rows for PayNet's UAT simulator banks (TEST0021, TEST0022,
 * TEST0023) with a recognizable display_name, so FpxController::bankList()
 * shows them properly labeled instead of falling back to a bare status
 * character (' ' or '(Offline)') that made them impossible to find in the
 * "Pilih Bank Anda" dropdown alongside 25+ real bank names.
 *
 * These three are real, permanent entries PayNet's own RetrieveBankList
 * returns on the UAT environment specifically for merchant testing — not
 * placeholder data.
 *
 * Idempotent: skips any code that already has a row (in case someone already
 * added these manually).
 */
return new class extends Migration
{
    public function up(): void
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
        }
    }

    public function down(): void
    {
        DB::table('fpx_banks')->whereIn('code', ['TEST0021', 'TEST0022', 'TEST0023'])->delete();
    }
};
