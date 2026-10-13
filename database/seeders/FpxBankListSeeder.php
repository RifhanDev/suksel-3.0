<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeds fpx_banks from PayNet's official "SMI Integration – Two (2) Domain
 * Method, FPX List of Financial Institution Code" (v4.6, effective
 * 14 Dec 2021, last updated 26 May 2026) — downloaded directly from
 * https://exchange.fpx.prod.inet.paynet.my/files/SMI%20-%20List%20of%20FPX%20Financial%20Institution%20Code%20v4.6.pdf,
 * combining the Staging + Production, B2C + B2B tables (union of all 4).
 *
 * A handful of codes have a DIFFERENT display name between B2C and B2B in
 * the source document, even though fpx_banks has no B2C/B2B distinction of
 * its own (bankList() looks a code up with no context of which mode it's
 * being shown for):
 *   BCBB0235: B2C "CIMB Clicks"  vs B2B "CIMB Bank"
 *   BKRM0602: B2C "Bank Rakyat"  vs B2B "i-bizRAKYAT"
 *   PBB0233:  B2C "Public Bank"  vs B2B "Public Bank PBe"
 * B2C is used below for all three, since msg_token '01' (B2C) is this
 * app's default mode.
 *
 * Safe to run repeatedly on any environment, including production, as part
 * of every deploy (see 2027_08_28_000002_run_fpx_bank_list_seeder.php, which
 * just calls this class — no data duplicated between the two files):
 *   - A code already present with IDENTICAL name/display_name/type/status
 *     is left completely untouched (no unnecessary write).
 *   - A code present with DIFFERENT data is updated in place (same id).
 *   - A code missing entirely is inserted.
 *   - A row whose code is NOT in this list is DEACTIVATED (status set to
 *     'inactive'), not deleted — fpx_banks already supports this via its
 *     'active' scope (status = 'active'), so it's reversible and doesn't
 *     destroy history for a bank PayNet removes and later re-adds.
 *   php artisan db:seed --class=FpxBankListSeeder
 */
class FpxBankListSeeder extends Seeder
{
    public function run(): void
    {
        $banks = [
            ['code' => 'ABB0233',  'name' => 'Affin Bank Berhad',                              'display_name' => 'Affin Bank'],
            ['code' => 'ABB0234',  'name' => 'Affin Bank Berhad B2C - Test ID',                 'display_name' => 'Affin B2C - Test ID'],
            ['code' => 'ABB0235',  'name' => 'Affin Bank Berhad B2B',                           'display_name' => 'AFFINMAX'],
            ['code' => 'ABMB0212', 'name' => 'Alliance Bank Malaysia Berhad',                   'display_name' => 'Alliance Bank (Personal)'],
            ['code' => 'ABMB0213', 'name' => 'Alliance Bank Malaysia Berhad',                   'display_name' => 'Alliance Bank (Business)'],
            ['code' => 'AGRO01',   'name' => 'Bank Pertanian Malaysia Berhad (Agrobank)',        'display_name' => 'AGRONet'],
            ['code' => 'AGRO02',   'name' => 'Bank Pertanian Malaysia Berhad (Agrobank)',        'display_name' => 'AGRONetBIZ'],
            ['code' => 'AMBB0209', 'name' => 'AmBank Malaysia Berhad',                          'display_name' => 'AmBank'],
            ['code' => 'AMBB0208', 'name' => 'AmBank Malaysia Berhad',                          'display_name' => 'AmBank'],
            ['code' => 'BCBB0235', 'name' => 'CIMB Bank Berhad',                                'display_name' => 'CIMB Clicks'],
            ['code' => 'BIMB0340', 'name' => 'Bank Islam Malaysia Berhad',                      'display_name' => 'Bank Islam'],
            ['code' => 'BKRM0602', 'name' => 'Bank Kerjasama Rakyat Malaysia Berhad',            'display_name' => 'Bank Rakyat'],
            ['code' => 'BMMB0341', 'name' => 'Bank Muamalat Malaysia Berhad',                   'display_name' => 'Bank Muamalat'],
            ['code' => 'BMMB0342', 'name' => 'Bank Muamalat Malaysia Berhad',                   'display_name' => 'Bank Muamalat'],
            ['code' => 'BNP003',   'name' => 'BNP Paribas Malaysia Berhad',                     'display_name' => 'BNP Paribas'],
            ['code' => 'BOCM01',   'name' => 'Bank Of China (M) Berhad',                        'display_name' => 'Bank Of China'],
            ['code' => 'BSN0601',  'name' => 'Bank Simpanan Nasional',                          'display_name' => 'BSN'],
            ['code' => 'CIT0218',  'name' => 'CITI Bank Berhad',                                'display_name' => 'Citibank Corporate Banking'],
            ['code' => 'CIT0219',  'name' => 'CITI Bank Berhad',                                'display_name' => 'Citibank'],
            ['code' => 'DBB0199',  'name' => 'Deutsche Bank Berhad',                            'display_name' => 'Deutsche Bank'],
            ['code' => 'HLB0224',  'name' => 'Hong Leong Bank Berhad',                          'display_name' => 'Hong Leong Bank'],
            ['code' => 'HSBC0223', 'name' => 'HSBC Bank Malaysia Berhad',                       'display_name' => 'HSBC Bank'],
            ['code' => 'KFH0346',  'name' => 'Kuwait Finance House (Malaysia) Berhad',          'display_name' => 'KFH'],
            ['code' => 'LOAD001',  'name' => 'Load Test Bank',                                  'display_name' => 'Load Bank'],
            ['code' => 'MB2U0227', 'name' => 'Malayan Banking Berhad (M2U)',                    'display_name' => 'Maybank2U'],
            ['code' => 'MBB0228',  'name' => 'Malayan Banking Berhad (M2E)',                    'display_name' => 'Maybank2E'],
            ['code' => 'MBBM2U2',  'name' => 'M2U Test Bank',                                   'display_name' => 'M2U Test'],
            ['code' => 'MBSB001',  'name' => 'MBSB Bank Berhad',                                'display_name' => 'MBSB Bank'],
            ['code' => 'OCBC0229', 'name' => 'OCBC Bank Malaysia Berhad',                       'display_name' => 'OCBC Bank'],
            ['code' => 'PBB0233',  'name' => 'Public Bank Berhad',                              'display_name' => 'Public Bank'],
            ['code' => 'PBB0234',  'name' => 'Public Bank Enterprise',                          'display_name' => 'Public Bank PB enterprise'],
            ['code' => 'RHB0218',  'name' => 'RHB Bank Berhad',                                 'display_name' => 'RHB Bank'],
            ['code' => 'SCB0216',  'name' => 'Standard Chartered Bank',                         'display_name' => 'Standard Chartered'],
            ['code' => 'SCB0215',  'name' => 'Standard Chartered Bank',                         'display_name' => 'Standard Chartered'],
            ['code' => 'TEST0021', 'name' => 'SBI Bank A',                                      'display_name' => 'SBI Bank A'],
            ['code' => 'TEST0022', 'name' => 'SBI Bank B',                                      'display_name' => 'SBI Bank B'],
            ['code' => 'TEST0023', 'name' => 'SBI Bank C',                                      'display_name' => 'SBI Bank C'],
            ['code' => 'UOB0226',  'name' => 'United Overseas Bank',                            'display_name' => 'UOB Bank'],
            ['code' => 'UOB0228',  'name' => 'United Overseas Bank B2B Regional',                'display_name' => 'UOB Regional'],
            ['code' => 'UOB0229',  'name' => 'United Overseas Bank - Test',                     'display_name' => 'UOB Bank - Test ID'],
        ];

        $desiredCodes = array_column($banks, 'code');
        $existingRows = DB::table('fpx_banks')->get()->keyBy('code');

        $created = 0;
        $updated = 0;

        foreach ($banks as $bank) {
            $row = $existingRows->get($bank['code']);

            if (!$row) {
                DB::table('fpx_banks')->insert([
                    'code'         => $bank['code'],
                    'name'         => $bank['name'],
                    'display_name' => $bank['display_name'],
                    'type'         => 'fpx',
                    'status'       => 'active',
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
                $created++;
                continue;
            }

            $needsUpdate = $row->name !== $bank['name']
                || $row->display_name !== $bank['display_name']
                || $row->type !== 'fpx'
                || $row->status !== 'active';

            if ($needsUpdate) {
                DB::table('fpx_banks')->where('id', $row->id)->update([
                    'name'         => $bank['name'],
                    'display_name' => $bank['display_name'],
                    'type'         => 'fpx',
                    'status'       => 'active',
                    'updated_at'   => now(),
                ]);
                $updated++;
            }
        }

        $deactivated = DB::table('fpx_banks')
            ->whereNotIn('code', $desiredCodes)
            ->where('status', '!=', 'inactive')
            ->update(['status' => 'inactive', 'updated_at' => now()]);

        $this->command?->info("FpxBankListSeeder: {$created} dicipta, {$updated} dikemaskini, {$deactivated} dinyahaktifkan (tiada dalam senarai rasmi).");
    }
}
