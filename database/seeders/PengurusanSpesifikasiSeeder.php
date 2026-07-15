<?php

namespace Database\Seeders;

use App\Models\Jawatankuasa;
use App\Support\TenderProcessStatus;
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

        $organizationUnitId = DB::table('organization_units')->value('id');

        if (! $organizationUnitId) {
            $this->command?->warn('PengurusanSpesifikasiSeeder: no organization unit found.');

            return;
        }

        $now = now();
        $creatorId = $userIds[0];

        $dummyTenders = [
            [
                'no_tender' => 'QT-DUMMY-PS-001',
                'ref_number' => 'DUMMY-PS-BEKALAN-001',
                'name' => '[DUMMY] Bekalan Peralatan Pejabat Dan ICT',
                'kategori_perolehan_id' => 1,
                'status_process_id' => TenderProcessStatus::PELANTIKAN_JAWATANKUASA,
                'checklistTables' => ['technical_checklist_headers', 'financial_checklist_headers'],
                'price' => 125000.00,
            ],
            [
                'no_tender' => 'QT-DUMMY-PS-002',
                'ref_number' => 'DUMMY-PS-KERJA-002',
                'name' => '[DUMMY] Kerja Naik Taraf Sistem Paip Dan Longkang',
                'kategori_perolehan_id' => 3,
                'status_process_id' => TenderProcessStatus::SPESIFIKASI_TEKNIKAL,
                'checklistTables' => ['spesifikasi_kerja_headers', 'kewangan_kerja_headers'],
                'price' => 2800000.00,
            ],
            [
                'no_tender' => 'QT-DUMMY-PS-003',
                'ref_number' => 'DUMMY-PS-PERKHIDMATAN-003',
                'name' => '[DUMMY] Perkhidmatan Penyelenggaraan Sistem Aplikasi',
                'kategori_perolehan_id' => 2,
                'status_process_id' => TenderProcessStatus::PELANTIKAN_JAWATANKUASA,
                'checklistTables' => ['technical_checklist_headers', 'financial_checklist_headers'],
                'price' => 450000.00,
            ],
        ];

        foreach ($dummyTenders as $config) {
            $this->seedDummyTender($config, $organizationUnitId, $creatorId, $userIds, $now);
        }

        // Refresh legacy test tenders when present.
        foreach ([81463, 81461] as $legacyId) {
            if (! DB::table('tenders')->where('id', $legacyId)->exists()) {
                continue;
            }

            $legacyConfig = $legacyId === 81463
                ? [
                    'lookup' => ['id' => 81463],
                    'update' => [
                        'no_tender' => 'QT210000000099999',
                        'ref_number' => 'SH-2026-999',
                        'name' => 'Sebut Harga Bekalan Peralatan ICT',
                        'kategori_perolehan_id' => 1,
                        'status_process_id' => TenderProcessStatus::SPESIFIKASI_TEKNIKAL,
                    ],
                    'jenisList' => ['spec', 'open', 'tech', 'fin'],
                    'resetChecklists' => ['technical_checklist_headers', 'financial_checklist_headers'],
                ]
                : [
                    'lookup' => ['id' => 81461],
                    'update' => [
                        'no_tender' => '33434',
                        'ref_number' => 'fgfg',
                        'name' => 'fcgfcgfg',
                        'kategori_perolehan_id' => 3,
                        'status_process_id' => TenderProcessStatus::SPESIFIKASI_TEKNIKAL,
                    ],
                    'jenisList' => ['spec', 'open', 'tech', 'fin'],
                    'resetChecklists' => ['spesifikasi_kerja_headers', 'kewangan_kerja_headers'],
                ];

            $this->refreshExistingTender($legacyConfig, $userIds, $now);
        }

        $this->command?->info('PengurusanSpesifikasiSeeder: dummy tenders QT-DUMMY-PS-001/002/003 ready for /pengurusan-spesifikasi.');
    }

    /**
     * @param  array<string, mixed>  $config
     * @param  list<int>  $userIds
     */
    private function seedDummyTender(array $config, int $organizationUnitId, int $creatorId, array $userIds, $notifiedAt): void
    {
        $existing = DB::table('tenders')->where('no_tender', $config['no_tender'])->first();

        if ($existing) {
            DB::table('tenders')->where('id', $existing->id)->update([
                'ref_number' => $config['ref_number'],
                'name' => $config['name'],
                'kategori_perolehan_id' => $config['kategori_perolehan_id'],
                'status_process_id' => $config['status_process_id'],
                'document_start_date' => now()->subDays(14)->toDateString(),
                'document_stop_date' => now()->addDays(30)->toDateString(),
                'submission_datetime' => now()->addDays(45)->format('Y-m-d H:i:s'),
                'price' => $config['price'],
                'updated_at' => now(),
            ]);
            $tenderId = (int) $existing->id;
        } else {
            $tenderId = (int) DB::table('tenders')->insertGetId([
                'uuid' => (string) Str::uuid(),
                'name' => $config['name'],
                'ref_number' => $config['ref_number'],
                'no_tender' => $config['no_tender'],
                'organization_unit_id' => $organizationUnitId,
                'creator_id' => $creatorId,
                'officer_id' => $creatorId,
                'kategori_perolehan_id' => $config['kategori_perolehan_id'],
                'status_process_id' => $config['status_process_id'],
                'document_start_date' => now()->subDays(14)->toDateString(),
                'document_stop_date' => now()->addDays(30)->toDateString(),
                'submission_datetime' => now()->addDays(45)->format('Y-m-d H:i:s'),
                'price' => $config['price'],
                'type' => 'quotation',
                'tender_peringkat' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->seedCommittee($tenderId, ['spec', 'open', 'tech', 'fin'], $userIds, $notifiedAt);
        $this->ensureDraftChecklists($tenderId, $config['checklistTables'], $creatorId);
    }

    /**
     * @param  array<string, mixed>  $config
     * @param  list<int>  $userIds
     */
    private function refreshExistingTender(array $config, array $userIds, $notifiedAt): void
    {
        $tender = DB::table('tenders')->where($config['lookup'])->first();

        if (! $tender) {
            return;
        }

        DB::table('tenders')->where('id', $tender->id)->update(array_merge($config['update'], [
            'updated_at' => now(),
        ]));

        $this->seedCommittee((int) $tender->id, $config['jenisList'], $userIds, $notifiedAt);
        $this->ensureDraftChecklists((int) $tender->id, $config['resetChecklists'], $userIds[0]);
    }

    /**
     * @param  list<string>  $jenisList
     * @param  list<int>  $userIds
     */
    private function seedCommittee(int $tenderId, array $jenisList, array $userIds, $notifiedAt): void
    {
        Jawatankuasa::query()->where('tender_id', $tenderId)->delete();

        $perananList = ['1', '2', '3'];
        $i = 0;

        foreach ($jenisList as $jenis) {
            foreach ($perananList as $peranan) {
                Jawatankuasa::create([
                    'tender_id' => $tenderId,
                    'jenis_jawatankuasa' => $jenis,
                    'p_p' => $peranan === '1' ? '1' : '0',
                    'peranan' => $peranan,
                    'user_id' => $userIds[$i % count($userIds)],
                    'dihantar_pemakluman_pada' => $notifiedAt,
                ]);
                $i++;
            }
        }
    }

    /**
     * @param  list<string>  $tables
     */
    private function ensureDraftChecklists(int $tenderId, array $tables, int $userId): void
    {
        foreach ($tables as $table) {
            $existing = DB::table($table)->where('tender_id', $tenderId)->first();

            if ($existing) {
                DB::table($table)->where('tender_id', $tenderId)->update([
                    'status' => 'draft',
                    'submitted_at' => null,
                    'submitted_by' => null,
                    'updated_at' => now(),
                ]);

                continue;
            }

            $row = [
                'uuid' => (string) Str::uuid(),
                'tender_id' => $tenderId,
                'status' => 'draft',
                'created_by' => $userId,
                'updated_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (in_array($table, ['technical_checklist_headers', 'financial_checklist_headers', 'kewangan_kerja_headers'], true)) {
                $row['max_score'] = 0;
                $row['passing_score'] = 0;
                $row['passing_percentage'] = 0;
            }

            DB::table($table)->insert($row);
        }
    }
}
