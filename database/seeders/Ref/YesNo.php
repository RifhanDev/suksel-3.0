<?php

namespace Database\Seeders\Ref;

/**
 * Senarai rujukan pilihan Ya/Tidak.
 *
 * Lihat RefListSeeder untuk kelakuan dan sebab ia selamat dijalankan berulang.
 */
class YesNo extends RefListSeeder
{
    public const ROWS = [
        ['name' => 'Ya'],
        ['name' => 'Tidak'],
    ];

    protected function table(): string
    {
        return 'ref_yes_nos';
    }

    protected function rows(): array
    {
        return self::ROWS;
    }
}
