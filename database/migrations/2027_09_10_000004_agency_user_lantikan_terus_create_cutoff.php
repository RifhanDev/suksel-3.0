<?php

use Database\Seeders\StosRolePermissionSeeder;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\PermissionRegistrar;

/**
 * Agency User (Pemilik Projek) for Lantikan Terus:
 * Cipta Projek + Senarai Projek + Cut Off only (mirrors Pembelian Terus).
 * Adds DirectAppointment:create|cutoff|quote|decision and syncs role perms.
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
        // Keep new permissions; detaching would drop Agency User Lantikan access.
    }
};
