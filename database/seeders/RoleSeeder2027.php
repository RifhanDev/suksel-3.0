<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder2027 extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create role 'Jawatankuasa'
        DB::table('roles')->updateOrInsert(
            ['name' => 'Jawatankuasa'],
            [
                'name'       => 'Jawatankuasa',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        $role = DB::table('roles')->where('name', 'Jawatankuasa')->first();

        // Assign permission 'Create tender specification' to the role
        $permission = DB::table('permissions')->where('name', 'specification:create')->first();

        if ($role && $permission) {
            DB::table('permission_role')->updateOrInsert(
                [
                    'permission_id' => $permission->id,
                    'role_id'       => $role->id,
                ],
                [
                    'permission_id' => $permission->id,
                    'role_id'       => $role->id,
                ]
            );
        }
    }
}
