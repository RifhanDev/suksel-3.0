<?php

namespace Database\Seeders\Ref;

/**
 * Senarai rujukan Sumber Peruntukan.
 *
 * Lihat RefListSeeder untuk kelakuan dan sebab ia selamat dijalankan berulang.
 */
class SumberPeruntukan extends RefListSeeder
{
    public const ROWS = [
        ['name' => 'Pembangunan'],
        ['name' => 'Mengurus'],
        ['name' => 'Lain-lain'],
    ];

    protected function table(): string
    {
        return 'ref_sumber_peruntukans';
    }

    protected function rows(): array
    {
        return self::ROWS;
    }
}
