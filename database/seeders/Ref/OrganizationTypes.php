<?php

namespace Database\Seeders\Ref;

/**
 * Senarai rujukan Jenis Organisasi.
 *
 * Lihat RefListSeeder untuk kelakuan dan sebab ia selamat dijalankan berulang.
     *
     * `is_ssm` dikekalkan tepat seperti seeder asal. Nilainya kelihatan
     * terbalik (ROB dan ROC ialah pendaftaran SSM, ROS bukan), tetapi itu
     * keputusan data perniagaan dan bukan tempat perubahan ini membetulkannya.
 */
class OrganizationTypes extends RefListSeeder
{
    public const ROWS = [
        ['name' => 'ROB: PERSEORANGAN', 'is_ssm' => 1],
        ['name' => 'ROB: PERKONGSIAN', 'is_ssm' => 0],
        ['name' => 'ROC: BERHAD', 'is_ssm' => 0],
        ['name' => 'ROC: SENDIRIAN BERHAD', 'is_ssm' => 0],
        ['name' => 'ROC: PERKONGSIAN LIABILITI TERHAD', 'is_ssm' => 0],
        ['name' => 'ROS: KOPERASI', 'is_ssm' => 1],
        ['name' => 'ROS: PERTUBUHAN', 'is_ssm' => 1],
        ['name' => 'ROS: PERSATUAN', 'is_ssm' => 1],
    ];

    protected function table(): string
    {
        return 'ref_organization_types';
    }

    protected function rows(): array
    {
        return self::ROWS;
    }
}
