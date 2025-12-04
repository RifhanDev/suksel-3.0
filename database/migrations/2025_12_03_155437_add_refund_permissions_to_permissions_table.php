<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $permissions = [
            [
                'name' => 'Refund:list',
                'display_name' => 'List Refund',
                'group_name' => 'Refund',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Refund:create',
                'display_name' => 'Create Refund',
                'group_name' => 'Refund',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Refund:store',
                'display_name' => 'Store Refund',
                'group_name' => 'Refund',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Refund:edit',
                'display_name' => 'Edit Refund',
                'group_name' => 'Refund',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Refund:update',
                'display_name' => 'Update Refund',
                'group_name' => 'Refund',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Refund:delete',
                'display_name' => 'Delete Refund',
                'group_name' => 'Refund',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Refund:show',
                'display_name' => 'Show Refund',
                'group_name' => 'Refund',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($permissions as $permission) {
            // Check if permission already exists
            $exists = DB::table('permissions')
                ->where('name', $permission['name'])
                ->exists();

            if (!$exists) {
                DB::table('permissions')->insert($permission);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $permissionNames = [
            'Refund:list',
            'Refund:create',
            'Refund:store',
            'Refund:edit',
            'Refund:update',
            'Refund:delete',
            'Refund:show',
        ];

        DB::table('permissions')
            ->whereIn('name', $permissionNames)
            ->delete();
    }
};
