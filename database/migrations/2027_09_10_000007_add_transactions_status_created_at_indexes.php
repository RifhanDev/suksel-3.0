<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Indeks untuk skrin /transactions.
 *
 * Jadual ini mempunyai lebih 1.4 juta baris di staging, dan skrin itu membaca
 * dengan dua cara yang kedua-duanya tiada indeks:
 *
 *   - Lima kad statistik masing-masing menjalankan COUNT(*) WHERE status = ?.
 *     Tiada indeks bermula dengan `status` — indeks komposit yang ada bermula
 *     dengan vendor_id — jadi setiap kiraan mengimbas seluruh jadual.
 *   - Senarai DataTable menyusun mengikut created_at DESC, dan tiada indeks
 *     bermula dengan lajur itu, jadi setiap halaman memaksa filesort penuh.
 *
 * (status, created_at) memenuhi kiraan DAN senarai yang ditapis mengikut status,
 * kerana MySQL boleh menggunakan awalan indeks untuk WHERE dan bakinya untuk
 * ORDER BY. (created_at) melayani senarai yang tidak ditapis.
 */
return new class extends Migration
{
    private const TABLE = 'transactions';

    /** @var array<string, string> nama indeks => senarai lajur */
    private const INDEXES = [
        'transactions_status_created_at_index' => '`status`, `created_at`',
        'transactions_created_at_index'        => '`created_at`',
    ];

    public function up(): void
    {
        if (! Schema::hasTable(self::TABLE)) {
            return;
        }

        // Jangan menunggu metadata lock tanpa had: DDL yang tersekat turut
        // menyekat setiap pertanyaan lain pada jadual ini.
        DB::statement('SET SESSION lock_wait_timeout = 60');

        foreach (self::INDEXES as $name => $columns) {
            if ($this->indexExists($name)) {
                continue;
            }

            $sql = sprintf('ALTER TABLE `%s` ADD INDEX `%s` (%s)', self::TABLE, $name, $columns);

            // INPLACE dengan LOCK=NONE membenarkan bacaan dan tulisan diteruskan
            // semasa indeks dibina — penting pada jadual sebesar ini.
            try {
                DB::statement($sql . ', ALGORITHM=INPLACE, LOCK=NONE');
            } catch (\Throwable $e) {
                if ($this->isLockTimeout($e)) {
                    throw $e;
                }

                DB::statement($sql);
            }
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable(self::TABLE)) {
            return;
        }

        foreach (array_keys(self::INDEXES) as $name) {
            if ($this->indexExists($name)) {
                DB::statement(sprintf('ALTER TABLE `%s` DROP INDEX `%s`', self::TABLE, $name));
            }
        }
    }

    private function indexExists(string $name): bool
    {
        return DB::table('information_schema.STATISTICS')
            ->where('TABLE_SCHEMA', DB::raw('DATABASE()'))
            ->where('TABLE_NAME', self::TABLE)
            ->where('INDEX_NAME', $name)
            ->exists();
    }

    private function isLockTimeout(\Throwable $e): bool
    {
        return str_contains($e->getMessage(), 'Lock wait timeout')
            || str_contains($e->getMessage(), 'lock_wait_timeout');
    }
};
