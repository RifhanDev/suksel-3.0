<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Mother Item rows store nama_item only; spesifikasi lives on child rows.
 * Allow NULL/empty spesifikasi on parent rows.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('spesifikasi_kerja_items') || ! Schema::hasColumn('spesifikasi_kerja_items', 'spesifikasi')) {
            return;
        }

        // Use raw alter for MySQL to avoid doctrine/dbal requirement.
        DB::statement('ALTER TABLE spesifikasi_kerja_items MODIFY spesifikasi TEXT NULL');
    }

    public function down(): void
    {
        if (! Schema::hasTable('spesifikasi_kerja_items') || ! Schema::hasColumn('spesifikasi_kerja_items', 'spesifikasi')) {
            return;
        }

        DB::table('spesifikasi_kerja_items')->whereNull('spesifikasi')->update(['spesifikasi' => '']);
        DB::statement('ALTER TABLE spesifikasi_kerja_items MODIFY spesifikasi TEXT NOT NULL');
    }
};
