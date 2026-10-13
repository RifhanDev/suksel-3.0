<?php

namespace Database\Seeders\Ref;

/**
 * Senarai rujukan Terbuka Kepada.
 *
 * Lihat RefListSeeder untuk kelakuan dan sebab ia selamat dijalankan berulang.
 */
class OpenTo extends RefListSeeder
{
    public const ROWS = [
        ['name' => 'Bumiputera'],
        ['name' => 'Semua'],
    ];

    protected function table(): string
    {
        return 'ref_open_tos';
    }

    protected function rows(): array
    {
        return self::ROWS;
    }
}
