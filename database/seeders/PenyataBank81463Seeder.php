<?php

namespace Database\Seeders;

use App\Models\PenyataBank;
use App\Models\PenyataBankScoringItem;
use App\Support\TenderProcessStatus;
use App\Tender;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Prepare tender #81463 for penyata bank testing (admin period config + vendor purchase).
 */
class PenyataBank81463Seeder extends Seeder
{
    public function run(): void
    {
        $tenderId = 81463;
        $tender = Tender::query()->find($tenderId);

        if (! $tender) {
            $this->command?->warn('PenyataBank81463Seeder: tender 81463 not found.');

            return;
        }

        DB::table('tenders')->where('id', $tenderId)->update([
            'name' => 'Sebut Harga Bekalan Peralatan ICT',
            'no_tender' => 'QT210000000099999',
            'ref_number' => 'SH-2026-999',
            'kategori_perolehan_id' => 1,
            'status_process_id' => TenderProcessStatus::SPESIFIKASI_TEKNIKAL,
            'document_start_date' => now()->subDays(7)->format('Y-m-d'),
            'document_stop_date' => now()->addDays(30)->format('Y-m-d'),
            'advertise_start_date' => now()->subDays(5)->format('Y-m-d'),
            'advertise_stop_date' => now()->addDays(25)->format('Y-m-d'),
            'submission_datetime' => now()->addDays(45)->format('Y-m-d H:i:s'),
            'updated_at' => now(),
        ]);

        $dariBulan = (int) now()->subMonths(2)->month;
        $dariTahun = (int) now()->subMonths(2)->year;
        $hinggaBulan = (int) now()->month;
        $hinggaTahun = (int) now()->year;

        $bulanTemplate = [];
        $cursor = now()->subMonths(2)->copy()->startOfMonth();
        $end = now()->copy()->startOfMonth();
        while ($cursor <= $end) {
            $bulanTemplate[] = [
                'bulan' => (int) $cursor->month,
                'tahun' => (int) $cursor->year,
                'jumlah' => 0,
            ];
            $cursor->addMonth();
        }

        $record = PenyataBank::query()->firstOrNew(['tender_id' => $tenderId]);
        if (! $record->exists) {
            $record->uuid = (string) Str::uuid();
            $record->created_by = 1;
        }

        $record->fill([
            'dari_bulan' => $dariBulan,
            'dari_tahun' => $dariTahun,
            'hingga_bulan' => $hinggaBulan,
            'hingga_tahun' => $hinggaTahun,
            'jumlah_keseluruhan' => 0,
            'purata' => 0,
            'jenis_skor_purata' => 'manual',
            'accounts' => [],
            'status' => 'draft',
            'updated_by' => 1,
        ])->save();

        $record->bulans()->delete();
        foreach ($bulanTemplate as $bulan) {
            $record->bulans()->create([
                'uuid' => (string) Str::uuid(),
                'bulan' => $bulan['bulan'],
                'tahun' => $bulan['tahun'],
                'jumlah' => 0,
            ]);
        }

        PenyataBankScoringItem::query()->where('penyata_bank_id', $record->id)->delete();
        foreach ([
            ['dari' => 0, 'hingga' => 10064.99, 'skema' => '0'],
            ['dari' => 10065, 'hingga' => 100205, 'skema' => '10'],
        ] as $index => $row) {
            PenyataBankScoringItem::query()->create([
                'uuid' => (string) Str::uuid(),
                'penyata_bank_id' => $record->id,
                'dari' => $row['dari'],
                'hingga' => $row['hingga'],
                'skema' => $row['skema'],
                'sort_order' => $index,
            ]);
        }

        $this->call(VendorTender81463Seeder::class);

        $uuid = $tender->uuid;
        $this->command?->info('PenyataBank81463Seeder: tender 81463 ready.');
        $this->command?->line('  Admin penyata bank : /penyata-bank/' . $uuid);
        $this->command?->line('  Senarai kewangan   : /senarai-kewangan-bekalan/' . $uuid);
        $this->command?->line('  Vendor (user #6)   : login as vendor then open tender dokumen tawaran');
    }
}
