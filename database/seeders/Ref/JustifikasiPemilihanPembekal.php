<?php

namespace Database\Seeders\Ref;

use Illuminate\Support\Facades\DB;

/**
 * Justifikasi Pemilihan Pembekal options for JP Kertas Keputusan.
 *
 * Extends RefListSeeder insert-if-missing behaviour, then syncs sort_order/active
 * so re-running the seeder refreshes display order without duplicating rows.
 */
class JustifikasiPemilihanPembekal extends RefListSeeder
{
    public const ROWS = [
        ['name' => 'Berpengalaman', 'sort_order' => 1],
        ['name' => 'Harga dalam lingkungan harga indikatif jabatan', 'sort_order' => 2],
        ['name' => 'Lain-lain', 'sort_order' => 3],
        ['name' => 'Lulus semua peringkat penilaian', 'sort_order' => 4],
        ['name' => 'menawarkan harga terendah', 'sort_order' => 5],
        ['name' => 'Tempoh penghantaran lebih cepat', 'sort_order' => 6],
        ['name' => 'Tiada beban dalam tangan', 'sort_order' => 7],
    ];

    protected function table(): string
    {
        return 'ref_justifikasi_pemilihan_pembekals';
    }

    protected function rows(): array
    {
        return self::ROWS;
    }

    public function run(): void
    {
        parent::run();

        foreach (self::ROWS as $row) {
            DB::table($this->table())
                ->where('name', $row['name'])
                ->update([
                    'sort_order' => $row['sort_order'],
                    'active' => 1,
                    'updated_at' => now(),
                ]);
        }
    }
}
