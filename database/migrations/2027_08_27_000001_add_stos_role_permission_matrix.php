<?php

use Database\Seeders\StosRolePermissionSeeder;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        (new StosRolePermissionSeeder())->run();
    }

    public function down(): void
    {
        (new StosRolePermissionSeeder())->rollback();
    }
};
