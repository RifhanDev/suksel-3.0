<?php

namespace Database\Seeders;

use App\Models\Jawatankuasa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PengurusanSpesifikasiSeeder extends Seeder
{
    /**
     * Seed tenders that qualify for /pengurusan-spesifikasi:
     * complete jawatankuasa + pemakluman, specification not fully submitted.
     */
    public function run(): void
    {
        $userIds = DB::table('users1')->pluck('id')->values()->all();

        if ($userIds === []) {
            $userIds = DB::table('users')->pluck('id')->values()->all();
        }

        if ($userIds === []) {
            $this->command?->warn('PengurusanSpesifikasiSeeder: no users found.');

            return;
        }

        $now = now();

        $this->seedTender([
            'lookup' => ['id' => 81463],
            'update' => [
                'uuid' => (string) Str::uuid(),
                'no_tender' => 'QT210000000099999',
                'ref_number' => 'SH-2026-999',
                'name' => 'Sebut Harga Bekalan Peralatan ICT',
                'document_start_date' => '2026-06-01',
                'document_stop_date' => '2026-06-30',
                'submission_datetime' => '2026-07-07 12:00:00',
                'kategori_perolehan_id' => 1,
                'status_process_id' => 4,
            ],
            'jenisList' => ['spec', 'open', 'tech', 'fin'],
            'resetChecklists' => ['technical_checklist_headers', 'financial_checklist_headers'],
            'userIds' => $userIds,
            'notifiedAt' => $now,
        ]);

        $this->seedTender([
            'lookup' => ['id' => 81461],
            'update' => [
                'uuid' => (string) Str::uuid(),
                'no_tender' => '33434',
                'ref_number' => 'fgfg',
                'name' => 'fcgfcgfg',
                'document_start_date' => '2026-04-15',
                'document_stop_date' => '2026-05-15',
                'submission_datetime' => '2026-05-06 12:00:00',
                'kategori_perolehan_id' => 3,
                'status_process_id' => 4,
            ],
            'jenisList' => ['spec', 'open', 'tech', 'fin'],
            'resetChecklists' => ['spesifikasi_kerja_headers', 'kewangan_kerja_headers'],
            'userIds' => $userIds,
            'notifiedAt' => $now,
        ]);

        $this->command?->info('PengurusanSpesifikasiSeeder: seeded tenders 81463 (Bekalan) and 81461 (Kerja).');
    }

    /**
     * @param  array<string, mixed>  $lookup
     * @param  array<string, mixed>  $update
     * @param  list<string>  $jenisList
     * @param  list<string>  $resetChecklists
     * @param  list<int>  $userIds
     */
    private function seedTender(array $config): void
    {
        $tender = DB::table('tenders')->where($config['lookup'])->first();

        if (! $tender) {
            $this->command?->warn('PengurusanSpesifikasiSeeder: tender not found: ' . json_encode($config['lookup']));

            return;
        }

        DB::table('tenders')->where('id', $tender->id)->update($config['update']);

        Jawatankuasa::query()->where('tender_id', $tender->id)->delete();

        $perananList = ['1', '2', '3'];
        $i = 0;

        foreach ($config['jenisList'] as $jenis) {
            foreach ($perananList as $peranan) {
                Jawatankuasa::create([
                    'tender_id' => $tender->id,
                    'jenis_jawatankuasa' => $jenis,
                    'p_p' => $peranan === '1' ? '1' : '0',
                    'peranan' => $peranan,
                    'user_id' => $config['userIds'][$i % count($config['userIds'])],
                    'dihantar_pemakluman_pada' => $config['notifiedAt'],
                ]);
                $i++;
            }
        }

        foreach ($config['resetChecklists'] as $table) {
            DB::table($table)
                ->where('tender_id', $tender->id)
                ->update(['status' => 'draft', 'updated_at' => now()]);
        }
    }
}
