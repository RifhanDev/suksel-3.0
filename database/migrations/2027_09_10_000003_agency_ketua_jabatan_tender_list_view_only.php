<?php

use Database\Seeders\StosRolePermissionSeeder;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\PermissionRegistrar;

/**
 * Agency Ketua Jabatan: Senarai Tender (view maklumat only).
 * Grants Tender:list for menu; view via Tender::canViewInternal / canShow.
 * Edit remains blocked (not in Tender:edit / canUpdate).
 */
return new class extends Migration
{
    public function up(): void
    {
        (new StosRolePermissionSeeder())->run();

        if (class_exists(PermissionRegistrar::class)) {
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }

    public function down(): void
    {
        // Keep Tender:list on Agency Ketua Jabatan — additive.
    }
};
