<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Indeks untuk /reports/vendor/transaction.
 *
 * Pada production yang sibuk, ALTER boleh kena lock wait timeout (1205) walaupun
 * ALGORITHM=INPLACE, LOCK=NONE — masih perlukan metadata lock sekejap. Jika gagal:
 *
 *   1. Cuba semula luar waktu puncak, ATAU
 *   2. Jalankan SQL manual (lihat komen di bawah), kemudian:
 *      php artisan migrate --path=database/migrations/2027_11_24_000001_add_vendor_transaction_report_indexes.php --force
 *      (migration ini idempotent — skip indeks yang sudah wujud)
 *
 * Manual SQL (jalankan satu demi satu):
 *
 *   SET SESSION lock_wait_timeout = 300;
 *   ALTER TABLE `tender_vendors`
 *     ADD INDEX `tender_vendors_transaction_id_index` (`transaction_id`),
 *     ALGORITHM=INPLACE, LOCK=NONE;
 *   ALTER TABLE `transactions`
 *     ADD INDEX `transactions_org_created_at_index` (`organization_unit_id`, `created_at`),
 *     ALGORITHM=INPLACE, LOCK=NONE;
 */
return new class extends Migration
{
    private const MAX_ATTEMPTS = 5;

    private const RETRY_SLEEP_SECONDS = 15;

    /** @var array<string, array{table: string, columns: string}> */
    private const INDEXES = [
        // Smaller / usually less contended first
        'transactions_org_created_at_index' => [
            'table' => 'transactions',
            'columns' => '`organization_unit_id`, `created_at`',
        ],
        'tender_vendors_transaction_id_index' => [
            'table' => 'tender_vendors',
            'columns' => '`transaction_id`',
        ],
    ];

    public function up(): void
    {
        // Metadata lock waits are the usual failure mode on a live app.
        DB::statement('SET SESSION lock_wait_timeout = 300');

        foreach (self::INDEXES as $name => $meta) {
            if (! Schema::hasTable($meta['table']) || $this->indexExists($meta['table'], $name)) {
                continue;
            }

            $this->addIndexWithRetry($meta['table'], $name, $meta['columns']);
        }
    }

    public function down(): void
    {
        foreach (self::INDEXES as $name => $meta) {
            if (! Schema::hasTable($meta['table']) || ! $this->indexExists($meta['table'], $name)) {
                continue;
            }

            DB::statement(sprintf('ALTER TABLE `%s` DROP INDEX `%s`', $meta['table'], $name));
        }
    }

    private function addIndexWithRetry(string $table, string $name, string $columns): void
    {
        $sql = sprintf(
            'ALTER TABLE `%s` ADD INDEX `%s` (%s)',
            $table,
            $name,
            $columns
        );

        $last = null;

        for ($attempt = 1; $attempt <= self::MAX_ATTEMPTS; $attempt++) {
            try {
                try {
                    DB::statement($sql . ', ALGORITHM=INPLACE, LOCK=NONE');
                } catch (\Throwable $e) {
                    if ($this->isLockTimeout($e)) {
                        throw $e;
                    }
                    // Some MySQL builds reject INPLACE/LOCK options — fall back.
                    DB::statement($sql);
                }

                return;
            } catch (\Throwable $e) {
                $last = $e;

                if (! $this->isLockTimeout($e) || $attempt === self::MAX_ATTEMPTS) {
                    throw $e;
                }

                sleep(self::RETRY_SLEEP_SECONDS);
            }
        }

        throw $last;
    }

    private function indexExists(string $table, string $name): bool
    {
        return DB::table('information_schema.STATISTICS')
            ->where('TABLE_SCHEMA', DB::raw('DATABASE()'))
            ->where('TABLE_NAME', $table)
            ->where('INDEX_NAME', $name)
            ->exists();
    }

    private function isLockTimeout(\Throwable $e): bool
    {
        $message = strtolower($e->getMessage());

        return str_contains($message, 'lock wait timeout')
            || str_contains($message, '1205');
    }
};
