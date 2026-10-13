<?php

use Database\Seeders\StosRolePermissionSeeder;
use Illuminate\Database\Migrations\Migration;

/**
 * Adds Ketua Jabatan role and attaches Pembelian Terus permissions
 * (DirectPurchase:list + DirectPurchase:select) so they can see
 * Pembelian Terus → Pemilihan Syarikat in the sidebar.
 */
return new class extends Migration
{
    public function up(): void
    {
        (new StosRolePermissionSeeder())->run();
    }

    public function down(): void
    {
        // Keep role/permissions — additive and may already be assigned to users.
    }
};
