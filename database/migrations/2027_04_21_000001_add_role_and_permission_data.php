<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function () {
            $timestamp = now();

            foreach ($this->newRoles() as $role) {
                $roleExists = DB::table('roles')
                    ->where('name', $role['name'])
                    ->exists();

                if ($roleExists) {
                    continue;
                }

                DB::table('roles')->insert([
                    'name' => $role['name'],
                    'guard_name' => $role['guard_name'] ?? 'web',
                    'display_name' => $role['display_name'] ?? null,
                    'description' => $role['description'] ?? null,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]);
            }

            foreach ($this->newPermissions() as $permission) {
                $permissionExists = DB::table('permissions')
                    ->where('name', $permission['name'])
                    ->exists();

                if ($permissionExists) {
                    continue;
                }

                DB::table('permissions')->insert([
                    'name' => $permission['name'],
                    'group_name' => $permission['group_name'],
                    'display_name' => $permission['display_name'] ?? null,
                    'description' => $permission['description'] ?? null,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]);
            }

            $this->attachRolePermissions($timestamp);
        });

        $this->forgetPermissionCache();
    }

    public function down(): void
    {
        DB::transaction(function () {
            $this->detachRolePermissions();

            $permissionNames = array_column($this->newPermissions(), 'name');
            if (!empty($permissionNames)) {
                DB::table('permissions')->whereIn('name', $permissionNames)->delete();
            }

            $roleNames = array_column($this->newRoles(), 'name');
            if (!empty($roleNames)) {
                DB::table('roles')->whereIn('name', $roleNames)->delete();
            }
        });

        $this->forgetPermissionCache();
    }

    private function attachRolePermissions($timestamp): void
    {
        $syncLegacyPivot = Schema::hasTable('permission_role');
        $syncSpatiePivot = Schema::hasTable('role_has_permissions');

        foreach ($this->rolePermissions() as $mapping) {
            $role = DB::table('roles')->where('name', $mapping['role'])->first();

            if (!$role) {
                throw new RuntimeException("Role [{$mapping['role']}] was not found while assigning permissions.");
            }

            foreach ($mapping['permissions'] as $permissionName) {
                $permission = DB::table('permissions')->where('name', $permissionName)->first();

                if (!$permission) {
                    throw new RuntimeException("Permission [{$permissionName}] was not found while assigning roles.");
                }

                if ($syncLegacyPivot) {
                    $legacyPivotExists = DB::table('permission_role')
                        ->where('permission_id', $permission->id)
                        ->where('role_id', $role->id)
                        ->exists();

                    if (!$legacyPivotExists) {
                        DB::table('permission_role')->insert([
                            'permission_id' => $permission->id,
                            'role_id' => $role->id,
                            'created_at' => $timestamp,
                            'updated_at' => $timestamp,
                        ]);
                    }
                }

                if ($syncSpatiePivot) {
                    $spatiePivotExists = DB::table('role_has_permissions')
                        ->where('permission_id', $permission->id)
                        ->where('role_id', $role->id)
                        ->exists();

                    if (!$spatiePivotExists) {
                        DB::table('role_has_permissions')->insert([
                            'permission_id' => $permission->id,
                            'role_id' => $role->id,
                        ]);
                    }
                }
            }
        }
    }

    private function detachRolePermissions(): void
    {
        $syncLegacyPivot = Schema::hasTable('permission_role');
        $syncSpatiePivot = Schema::hasTable('role_has_permissions');

        foreach ($this->rolePermissions() as $mapping) {
            $role = DB::table('roles')->where('name', $mapping['role'])->first();

            if (!$role) {
                continue;
            }

            $permissionIds = DB::table('permissions')
                ->whereIn('name', $mapping['permissions'])
                ->pluck('id');

            if ($permissionIds->isEmpty()) {
                continue;
            }

            if ($syncLegacyPivot) {
                DB::table('permission_role')
                    ->where('role_id', $role->id)
                    ->whereIn('permission_id', $permissionIds)
                    ->delete();
            }

            if ($syncSpatiePivot) {
                DB::table('role_has_permissions')
                    ->where('role_id', $role->id)
                    ->whereIn('permission_id', $permissionIds)
                    ->delete();
            }
        }
    }

    private function forgetPermissionCache(): void
    {
        if (class_exists(PermissionRegistrar::class)) {
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }

    private function newRoles(): array
    {
        return [
            // Add only brand-new roles here.
            // Do not put an existing role here unless you want rollback to delete it.
            [
                'name' => 'Jawatankuasa',
                'display_name' => 'Jawatankuasa',
            ],
            [
                'name' => 'Jawatan Kuasa Spesifikasi',
                'display_name' => 'Jawatan Kuasa Spesifikasi',
            ],
        ];
    }

    private function newPermissions(): array
    {
        return [
            // Add only brand-new permissions here.
            [
                'name' => 'Tender:execute',
                'group_name' => 'Tender',
                'display_name' => 'Execute tender',
            ],
            [
                'name' => 'committee:create',
                'group_name' => 'Tender',
                'display_name' => 'Create tender committee',
            ],
            [
                'name' => 'Tender:specification-management',
                'group_name' => 'Tender',
                'display_name' => 'Manage Tender specification',
            ],
            [
                'name' => 'specification:create',
                'group_name' => 'Tender',
                'display_name' => 'Create tender specification',
            ],
        ];
    }

    private function rolePermissions(): array
    {
        return [
            // You can map permissions to either a new role above or an existing role.
            [
                'role' => 'Admin',
                'permissions' => array_column($this->newPermissions(), 'name'),
            ],
            [
                'role' => 'Jawatankuasa',
                'permissions' => [
                    'specification:create',
                ],
            ],
            [
                'role' => 'Jawatan Kuasa Spesifikasi',
                'permissions' => [
                    'tender:specification-management',
                ],
            ],
        ];
    }
};
