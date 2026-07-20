<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks to safely truncate (adjust if unsafe in your app)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('roles')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $rows = [
            [
                'id' => 1,
                'name' => 'Admin',
                'created_at' => '2015-05-26 09:03:13',
                'updated_at' => '2021-10-28 05:37:23',
            ],
            [
                'id' => 2,
                'name' => 'Agency Admin',
                'created_at' => '2015-05-26 09:03:13',
                'updated_at' => '2023-12-05 03:22:40',
            ],
            [
                'id' => 3,
                'name' => 'Agency User',
                'created_at' => '2015-05-26 09:03:13',
                'updated_at' => '2023-12-05 04:03:13',
            ],
            [
                'id' => 5,
                'name' => 'Vendor',
                'created_at' => '2015-05-26 09:03:13',
                'updated_at' => '2019-12-05 08:50:14',
            ],
            [
                'id' => 6,
                'name' => 'Admin UPEN',
                'created_at' => '2016-08-02 02:49:19',
                'updated_at' => '2025-03-24 03:49:02',
            ],
            [
                'id' => 7,
                'name' => 'Admin PWN',
                'created_at' => '2017-08-17 00:30:44',
                'updated_at' => '2017-08-17 00:30:44',
            ],
            [
                'id' => 8,
                'name' => 'Admin Kewangan',
                'created_at' => '2017-09-07 02:39:16',
                'updated_at' => '2025-07-29 02:27:41',
            ],
            [
                'id' => 10,
                'name' => 'Nyahaktif',
                'created_at' => '2020-10-06 02:13:36',
                'updated_at' => '2021-10-28 04:40:19',
            ],
        ];

        DB::table('roles')->insert($rows);
    }
}