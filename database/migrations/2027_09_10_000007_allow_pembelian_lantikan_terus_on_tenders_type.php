<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Server DB (etender) still has tenders.type as ENUM without pembelian_terus /
 * lantikan_terus, so creating Pembelian/Lantikan Terus fails with:
 *   SQLSTATE[01000]: Warning: 1265 Data truncated for column 'type'
 *
 * Local already uses varchar(255). Align server to that so new module types
 * can be stored without another ENUM alter each time.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tenders') || ! Schema::hasColumn('tenders', 'type')) {
            return;
        }

        $column = DB::selectOne(
            'SELECT COLUMN_TYPE, IS_NULLABLE, COLUMN_DEFAULT
             FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = ?
               AND COLUMN_NAME = ?',
            ['tenders', 'type']
        );

        if (! $column) {
            return;
        }

        $columnType = strtolower((string) $column->COLUMN_TYPE);

        // Already a free-form string (local / already migrated).
        if (str_starts_with($columnType, 'varchar') || str_starts_with($columnType, 'text')) {
            return;
        }

        $nullable = strtoupper((string) $column->IS_NULLABLE) === 'YES' ? 'NULL' : 'NOT NULL';

        // Prefer varchar to match local and avoid ENUM drift for module types.
        DB::statement("ALTER TABLE `tenders` MODIFY `type` VARCHAR(255) {$nullable}");
    }

    public function down(): void
    {
        // Irreversible safely: previous ENUM values differ per environment.
        // Leave type as VARCHAR(255).
    }
};
