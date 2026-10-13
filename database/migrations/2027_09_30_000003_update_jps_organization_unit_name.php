<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('organization_units')) {
            return;
        }

        DB::table('organization_units')
            ->where('id', 24)
            ->where('short_name', 'JPS')
            ->update(['name' => 'Jabatan Pengairan dan Saliran Negeri Selangor']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('organization_units')) {
            return;
        }

        DB::table('organization_units')
            ->where('id', 24)
            ->where('short_name', 'JPS')
            ->update(['name' => 'Jabatan Pengairan dan Saliran']);
    }
};
