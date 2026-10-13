<?php

use Database\Seeders\StosRolePermissionSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;

/**
 * Agency Ketua Jabatan: Pembelian Terus / Lantikan Terus → Pemilihan Syarikat only.
 * Detach DirectPurchase:list and DirectAppointment:list from that role.
 */
return new class extends Migration
{
    public function up(): void
    {
        (new StosRolePermissionSeeder())->run();

        $roleId = DB::table('roles')->where('name', 'Agency Ketua Jabatan')->value('id');
        $listIds = DB::table('permissions')
            ->whereIn('name', ['DirectPurchase:list', 'DirectAppointment:list'])
            ->pluck('id');

        if ($roleId && $listIds->isNotEmpty()) {
            if (Schema::hasTable('permission_role')) {
                DB::table('permission_role')
                    ->where('role_id', $roleId)
                    ->whereIn('permission_id', $listIds)
                    ->delete();
            }

            if (Schema::hasTable('role_has_permissions')) {
                DB::table('role_has_permissions')
                    ->where('role_id', $roleId)
                    ->whereIn('permission_id', $listIds)
                    ->delete();
            }
        }

        if (class_exists(PermissionRegistrar::class)) {
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }

    public function down(): void
    {
        // Keep select-only restriction.
    }
};
