<?php

namespace Database\Seeders\Ref;

/**
 * Senarai rujukan Jenis Tender / Sebut Harga.
 *
 * Lihat RefListSeeder untuk kelakuan dan sebab ia selamat dijalankan berulang.
 */
class TypeOfTender extends RefListSeeder
{
    public const ROWS = [
        ['name' => 'Konvensional'],
        ['name' => 'Reka & Bina'],
        ['name' => 'Terhad'],
    ];

    protected function table(): string
    {
        return 'ref_type_of_tenders';
    }

    protected function rows(): array
    {
        return self::ROWS;
    }
}
