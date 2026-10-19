<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Melonggarkan NOT NULL pada tenders.ref_number.
 *
 * Borang cipta tender 3.0 tidak mewajibkan No. Rujukan Fail. Pada sesetengah
 * pangkalan (dump 2.0 / alter manual) lajur ini kekal NOT NULL, jadi cipta
 * tender gagal dengan SQLSTATE[23000] 1048 Column 'ref_number' cannot be null.
 *
 * Migration asal create_tender_table sudah nullable; ini menyelaraskan skema
 * produksi tanpa membuang data sedia ada.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tenders') || ! Schema::hasColumn('tenders', 'ref_number')) {
            return;
        }

        $this->setNullable('tenders', ['ref_number'], true);
    }

    public function down(): void
    {
        if (! Schema::hasTable('tenders') || ! Schema::hasColumn('tenders', 'ref_number')) {
            return;
        }

        // Jangan paksa NOT NULL jika sudah ada baris NULL — rollback akan gagal.
        if (DB::table('tenders')->whereNull('ref_number')->exists()) {
            return;
        }

        $this->setNullable('tenders', ['ref_number'], false);
    }

    /**
     * @param  list<string>  $columns
     */
    private function setNullable(string $table, array $columns, bool $nullable): void
    {
        if ($columns === []) {
            return;
        }

        $placeholders = implode(',', array_fill(0, count($columns), '?'));

        $info = DB::select(
            "SELECT COLUMN_NAME AS column_name, COLUMN_TYPE AS column_type,
                    IS_NULLABLE AS is_nullable, COLUMN_DEFAULT AS column_default,
                    EXTRA AS extra
               FROM information_schema.COLUMNS
              WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = ?
                AND COLUMN_NAME IN ({$placeholders})",
            array_merge([$table], $columns)
        );

        $clauses = [];

        foreach ($info as $column) {
            if ((strtoupper($column->is_nullable) === 'YES') === $nullable) {
                continue;
            }

            $clause = sprintf(
                'MODIFY `%s` %s %s',
                $column->column_name,
                $column->column_type,
                $nullable ? 'NULL' : 'NOT NULL'
            );

            if ($column->column_default !== null) {
                $clause .= $this->isExpressionDefault($column->column_default)
                    ? ' DEFAULT ' . $column->column_default
                    : ' DEFAULT ' . DB::getPdo()->quote($column->column_default);
            }

            if (! empty($column->extra) && stripos($column->extra, 'on update') !== false) {
                $clause .= ' ' . $column->extra;
            }

            $clauses[] = $clause;
        }

        if ($clauses === []) {
            return;
        }

        $sql = sprintf('ALTER TABLE `%s` %s', $table, implode(', ', $clauses));

        DB::statement('SET SESSION lock_wait_timeout = 60');

        try {
            DB::statement($sql . ', ALGORITHM=INPLACE, LOCK=NONE');
        } catch (\Throwable $e) {
            if ($this->isLockTimeout($e)) {
                throw $e;
            }

            DB::statement($sql);
        }
    }

    private function isLockTimeout(\Throwable $e): bool
    {
        return (bool) preg_match('/\b(1205|3572)\b|lock wait timeout|metadata lock/i', $e->getMessage());
    }

    private function isExpressionDefault(string $default): bool
    {
        return (bool) preg_match('/^(CURRENT_TIMESTAMP|NOW\(\)|CURRENT_DATE)/i', $default);
    }
};
