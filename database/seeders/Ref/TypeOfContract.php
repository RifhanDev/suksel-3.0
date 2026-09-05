<?php

namespace Database\Seeders\Ref;

/**
 * Senarai rujukan Jenis Kontrak.
 *
 * Lihat RefListSeeder untuk kelakuan dan sebab ia selamat dijalankan berulang.
 * Jadual ini dirujuk oleh tenders.jenis_kontrak_id dengan onDelete('set null'),
 * jadi tiada baris pernah dipadam di sini.
 */
class TypeOfContract extends RefListSeeder
{
    public const ROWS = [
        ['name' => 'Kementerian'],
        ['name' => 'Bukan Kementerian'],
    ];

    protected function table(): string
    {
        return 'ref_type_of_contracts';
    }

    protected function rows(): array
    {
        return self::ROWS;
    }
}
