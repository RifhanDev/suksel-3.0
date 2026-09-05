<?php

use Database\Seeders\StosRolePermissionSeeder;
use Illuminate\Database\Migrations\Migration;

/**
 * Adds granular Pembelian Terus step permissions and role attachments:
 * create/cutoff (Pemilik Projek), select (Ketua Jabatan), quote/decision (Syarikat).
 *
 * Re-runs StosRolePermissionSeeder which upserts missing permissions and
 * attaches any missing role_has_permissions / permission_role rows without
 * deleting existing ones.
 */
return new class extends Migration
{
    public function up(): void
    {
        (new StosRolePermissionSeeder())->run();
    }

    public function down(): void
    {
        // Seeder rollback would detach all STOS matrix perms — too broad.
        // Leave permissions in place; they are additive and safe to keep.
    }
};
