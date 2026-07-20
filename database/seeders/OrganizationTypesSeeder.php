<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('organization_types')->truncate();

        DB::table('organization_types')->insert([
            [
                'id' => 1,
                'name' => 'Pejabat SUK',
                'created_at' => '2015-06-06 05:53:02',
                'updated_at' => '2023-05-23 19:15:12',
                'sort_no'    => 2,
                'ori_id'     => null,
            ],
            [
                'id' => 2,
                'name' => 'Pejabat Daerah',
                'created_at' => '2015-06-06 05:53:02',
                'updated_at' => '2023-05-23 19:15:12',
                'sort_no'    => 3,
                'ori_id'     => null,
            ],
            [
                'id' => 3,
                'name' => 'PBT',
                'created_at' => '2015-06-06 05:53:02',
                'updated_at' => '2023-05-23 19:15:12',
                'sort_no'    => 4,
                'ori_id'     => null,
            ],
            [
                'id' => 4,
                'name' => 'Jabatan Lain',
                'created_at' => '2015-06-06 05:53:02',
                'updated_at' => '2023-05-23 19:15:12',
                'sort_no'    => 8,
                'ori_id'     => null,
            ],
            [
                'id' => 5,
                'name' => 'Perbendaharaan Negeri Selangor',
                'created_at' => '2023-05-23 19:13:58',
                'updated_at' => '2023-05-23 19:15:12',
                'sort_no'    => 1,
                'ori_id'     => null,
            ],
            [
                'id' => 6,
                'name' => 'Jabatan Kerja Raya Negeri Selangor',
                'created_at' => '2023-05-23 19:14:14',
                'updated_at' => '2023-05-23 19:15:12',
                'sort_no'    => 5,
                'ori_id'     => null,
            ],
            [
                'id' => 7,
                'name' => 'Jabatan Pengairan dan Saliran',
                'created_at' => '2023-05-23 19:14:23',
                'updated_at' => '2023-05-23 19:15:12',
                'sort_no'    => 6,
                'ori_id'     => null,
            ],
            [
                'id' => 8,
                'name' => 'Jabatan di bawah Kerajaan Negeri Selangor',
                'created_at' => '2023-05-23 19:14:36',
                'updated_at' => '2023-05-23 19:15:12',
                'sort_no'    => 7,
                'ori_id'     => null,
            ],
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('✔ organization_types table seeded successfully.');
    }
}
