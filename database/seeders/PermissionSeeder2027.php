<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder2027 extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('permissions')->updateOrInsert(
            ['name' => 'Tender:execute'],
            [
                'group_name' => 'Tender',
                'display_name' => 'Execute tender',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
        DB::table('permissions')->updateOrInsert(
            ['name' => 'specification:create'],
            [
                'group_name' => 'Tender specification',
                'display_name' => 'Create tender specification',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }
}
