<?php

namespace Database\Seeders\Ref;

/**
 * Senarai rujukan Jenis Pemenuhan.
 *
 * Lihat RefListSeeder untuk kelakuan dan sebab ia selamat dijalankan berulang.
 */
class TypeOfPemenuhan extends RefListSeeder
{
    public const ROWS = [
        ['name' => 'Bermasa (Bila Perlu)'],
        ['name' => 'Sepenuh Masa'],
    ];

    protected function table(): string
    {
        return 'ref_type_of_pemenuhans';
    }

    protected function rows(): array
    {
        return self::ROWS;
    }
}
