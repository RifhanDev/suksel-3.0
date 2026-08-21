<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

/**
 * Production's `tenders`, `users`, `vendors`, `organization_units` tables predate this app's
 * Laravel migrations and use `int unsigned` primary keys (guarded by the
 * `if (Schema::hasTable(...)) return;` skip at the top of each of their create-table
 * migrations, so `up()` there never actually runs against production). A fresh local
 * `migrate` instead builds those 4 tables via the modern `$table->id()` default
 * (`bigint unsigned`), and every FK column that references them inherits that same bigint
 * width — 81 columns across the schema on local as of 2027-08-20, confirmed via
 * information_schema.
 *
 * This migration is idempotent and safe to run on ANY environment, including production:
 * it inspects the live schema first and does nothing if the 4 parent tables are already
 * `int unsigned`. It only performs the down-conversion where the live schema still shows
 * `bigint` — i.e. today, that means local only.
 *
 * WARNING: MySQL DDL is not transactional. If this fails partway through, the schema is
 * left in a mixed state (some FKs dropped/rebuilt, some not) and needs manual review before
 * re-running. Take a DB backup/snapshot before running this anywhere with data you care
 * about.
 */
return new class extends Migration
{
    private const LEGACY_TABLES = ['tenders', 'users', 'vendors', 'organization_units'];

    public function up(): void
    {
        $tablesNeedingFix = array_values(array_filter(self::LEGACY_TABLES, function ($table) {
            if (! Schema::hasTable($table)) {
                return false;
            }

            $col = DB::selectOne(
                "SELECT COLUMN_TYPE FROM information_schema.columns
                 WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = 'id'",
                [$table]
            );

            return $col && stripos($col->COLUMN_TYPE, 'bigint') !== false;
        }));

        if (empty($tablesNeedingFix)) {
            Log::info('[fix legacy pk] all 4 legacy tables already int unsigned — nothing to do.');
            return;
        }

        // Safety guard: if any parent id already holds a value past INT UNSIGNED's max
        // (4294967295), narrowing would silently truncate/fail. Abort loudly instead.
        foreach ($tablesNeedingFix as $table) {
            $max = DB::selectOne("SELECT MAX(id) AS max_id FROM `{$table}`");
            if ($max && $max->max_id !== null && (int) $max->max_id > 4294967295) {
                throw new \RuntimeException(
                    "[fix legacy pk] {$table}.id has a value ({$max->max_id}) that does not fit " .
                    'INT UNSIGNED — aborting before making any change. This environment genuinely ' .
                    'needs bigint and should not run this migration; investigate before proceeding.'
                );
            }
        }

        Log::info('[fix legacy pk] converting bigint -> int unsigned for: ' . implode(', ', $tablesNeedingFix));

        $placeholders = implode(',', array_fill(0, count($tablesNeedingFix), '?'));

        $fks = DB::select(
            "SELECT k.CONSTRAINT_NAME, k.TABLE_NAME, k.COLUMN_NAME, k.REFERENCED_TABLE_NAME, k.REFERENCED_COLUMN_NAME,
                    r.DELETE_RULE, r.UPDATE_RULE, c.IS_NULLABLE
             FROM information_schema.key_column_usage k
             JOIN information_schema.referential_constraints r
               ON r.CONSTRAINT_SCHEMA = k.TABLE_SCHEMA AND r.CONSTRAINT_NAME = k.CONSTRAINT_NAME AND r.TABLE_NAME = k.TABLE_NAME
             JOIN information_schema.columns c
               ON c.TABLE_SCHEMA = k.TABLE_SCHEMA AND c.TABLE_NAME = k.TABLE_NAME AND c.COLUMN_NAME = k.COLUMN_NAME
             WHERE k.TABLE_SCHEMA = DATABASE()
               AND k.REFERENCED_TABLE_NAME IN ($placeholders)",
            $tablesNeedingFix
        );

        // 1. Drop every FK constraint pointing at the tables being fixed — MySQL won't allow
        //    narrowing a parent PK (or a child FK column) while a constraint still spans it.
        foreach ($fks as $fk) {
            DB::statement("ALTER TABLE `{$fk->TABLE_NAME}` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
        }

        // 2. Narrow the parent PK columns.
        foreach ($tablesNeedingFix as $table) {
            DB::statement("ALTER TABLE `{$table}` MODIFY `id` INT UNSIGNED NOT NULL AUTO_INCREMENT");
        }

        // 3. Narrow every dependent FK column to match, preserving its nullability.
        foreach ($fks as $fk) {
            $null = $fk->IS_NULLABLE === 'YES' ? 'NULL' : 'NOT NULL';
            DB::statement("ALTER TABLE `{$fk->TABLE_NAME}` MODIFY `{$fk->COLUMN_NAME}` INT UNSIGNED {$null}");
        }

        // 4. Recreate every FK constraint with its original name and ON DELETE/UPDATE rules.
        foreach ($fks as $fk) {
            DB::statement(
                "ALTER TABLE `{$fk->TABLE_NAME}` ADD CONSTRAINT `{$fk->CONSTRAINT_NAME}` " .
                "FOREIGN KEY (`{$fk->COLUMN_NAME}`) REFERENCES `{$fk->REFERENCED_TABLE_NAME}` (`{$fk->REFERENCED_COLUMN_NAME}`) " .
                "ON DELETE {$fk->DELETE_RULE} ON UPDATE {$fk->UPDATE_RULE}"
            );
        }

        Log::info('[fix legacy pk] done — rebuilt ' . count($fks) . ' foreign key constraints across ' . count($tablesNeedingFix) . ' tables.');
    }

    public function down(): void
    {
        // Deliberately not implemented: reversing this (widening back to bigint) needs the
        // exact same drop/alter/recreate dance, and there's no scenario where you'd want to
        // reintroduce the mismatch this migration exists to remove. Restore from a backup
        // taken before running this migration if you need to undo it.
    }
};
