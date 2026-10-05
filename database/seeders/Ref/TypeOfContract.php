<?php

namespace Database\Seeders\Ref;

use Illuminate\Support\Facades\DB;

/**
 * Senarai rujukan Jenis Kontrak.
 *
 * Lihat RefListSeeder untuk kelakuan dan sebab ia selamat dijalankan berulang.
 * Jadual ini dirujuk oleh tenders.jenis_kontrak_id dengan onDelete('set null').
 */
class TypeOfContract extends RefListSeeder
{
    public const ROWS = [
        ['id' => 1, 'name' => 'Kementerian'],
        ['id' => 2, 'name' => 'Kerajaan Negeri'],
        ['id' => 3, 'name' => 'Agensi dan Jabatan'],
        ['id' => 4, 'name' => 'Lain-lain'],
    ];

    protected function table(): string
    {
        return 'ref_type_of_contracts';
    }

    protected function rows(): array
    {
        return self::ROWS;
    }

    public function run(): void
    {
        foreach ($this->rows() as $row) {
            DB::table($this->table())->updateOrInsert(
                ['id' => $row['id']],
                [
                    'name'       => $row['name'],
                    'active'     => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // Remove any extra rows beyond ID 4
        DB::table($this->table())->where('id', '>', 4)->delete();
    }
}
