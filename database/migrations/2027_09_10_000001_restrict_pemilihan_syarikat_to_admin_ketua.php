<?php

use Database\Seeders\StosRolePermissionSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;

/**
 * Pemilihan Syarikat (Pembelian Terus + Lantikan Terus):
 * - Admin + Ketua Jabatan only
 * - Detach DirectPurchase:select from Agency Admin
 * - Add DirectAppointment:select and attach to Admin + Ketua Jabatan
 */
return new class extends Migration
{
    public function up(): void
    {
        (new StosRolePermissionSeeder())->run();

        $selectPurchaseId = DB::table('permissions')->where('name', 'DirectPurchase:select')->value('id');
        $agencyAdminId = DB::table('roles')->where('name', 'Agency Admin')->value('id');

        if ($selectPurchaseId && $agencyAdminId) {
            if (Schema::hasTable('permission_role')) {
                DB::table('permission_role')
                    ->where('permission_id', $selectPurchaseId)
                    ->where('role_id', $agencyAdminId)
                    ->delete();
            }

            if (Schema::hasTable('role_has_permissions')) {
                DB::table('role_has_permissions')
                    ->where('permission_id', $selectPurchaseId)
                    ->where('role_id', $agencyAdminId)
                    ->delete();
            }
        }

        if (class_exists(PermissionRegistrar::class)) {
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }

    public function down(): void
    {
        // Keep permissions; re-attaching Agency Admin select would undo the restriction.
    }
};
