<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;

/**
 * Agency Admin may open Senarai Hitam + Senarai Transaksi.
 */
return new class extends Migration
{
    private array $permissions = [
        'Blacklist:index',
        'Transaction:list',
        'Transaction:show',
    ];

    public function up(): void
    {
        $roleId = DB::table('roles')->where('name', 'Agency Admin')->value('id');
        if (! $roleId) {
            return;
        }

        $permissionIds = DB::table('permissions')
            ->whereIn('name', $this->permissions)
            ->pluck('id');

        if ($permissionIds->isEmpty()) {
            return;
        }

        $timestamp = now();

        foreach ($permissionIds as $permissionId) {
            if (Schema::hasTable('permission_role')) {
                $exists = DB::table('permission_role')
                    ->where('permission_id', $permissionId)
                    ->where('role_id', $roleId)
                    ->exists();

                if (! $exists) {
                    $row = [
                        'permission_id' => $permissionId,
                        'role_id' => $roleId,
                    ];
                    if (Schema::hasColumn('permission_role', 'created_at')) {
                        $row['created_at'] = $timestamp;
                    }
                    if (Schema::hasColumn('permission_role', 'updated_at')) {
                        $row['updated_at'] = $timestamp;
                    }
                    DB::table('permission_role')->insert($row);
                }
            }

            if (Schema::hasTable('role_has_permissions')) {
                $exists = DB::table('role_has_permissions')
                    ->where('permission_id', $permissionId)
                    ->where('role_id', $roleId)
                    ->exists();

                if (! $exists) {
                    DB::table('role_has_permissions')->insert([
                        'permission_id' => $permissionId,
                        'role_id' => $roleId,
                    ]);
                }
            }
        }

        if (class_exists(PermissionRegistrar::class)) {
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }

    public function down(): void
    {
        $roleId = DB::table('roles')->where('name', 'Agency Admin')->value('id');
        if (! $roleId) {
            return;
        }

        $permissionIds = DB::table('permissions')
            ->whereIn('name', $this->permissions)
            ->pluck('id');

        if ($permissionIds->isEmpty()) {
            return;
        }

        if (Schema::hasTable('permission_role')) {
            DB::table('permission_role')
                ->where('role_id', $roleId)
                ->whereIn('permission_id', $permissionIds)
                ->delete();
        }

        if (Schema::hasTable('role_has_permissions')) {
            DB::table('role_has_permissions')
                ->where('role_id', $roleId)
                ->whereIn('permission_id', $permissionIds)
                ->delete();
        }

        if (class_exists(PermissionRegistrar::class)) {
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }
};
