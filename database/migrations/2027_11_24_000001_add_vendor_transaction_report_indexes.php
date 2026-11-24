<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Indeks untuk /reports/vendor/transaction.
 *
 * Laporan itu menapis transactions mengikut organization_unit_id + julat created_at,
 * kemudian join tender_vendors pada transaction_id. Tiada indeks yang bermula
 * dengan transaction_id pada tender_vendors, jadi setiap join/imbasan detail
 * menjadi penuh. (organization_unit_id, created_at) pada transactions juga
 * merapatkan penapis agensi + tempoh.
 */
return new class extends Migration
{
    /** @var array<string, array{table: string, columns: string}> */
    private const INDEXES = [
        'tender_vendors_transaction_id_index' => [
            'table' => 'tender_vendors',
            'columns' => '`transaction_id`',
        ],
        'transactions_org_created_at_index' => [
            'table' => 'transactions',
            'columns' => '`organization_unit_id`, `created_at`',
        ],
    ];

    public function up(): void
    {
        DB::statement('SET SESSION lock_wait_timeout = 60');

        foreach (self::INDEXES as $name => $meta) {
            if (! Schema::hasTable($meta['table']) || $this->indexExists($meta['table'], $name)) {
                continue;
            }

            $sql = sprintf(
                'ALTER TABLE `%s` ADD INDEX `%s` (%s)',
                $meta['table'],
                $name,
                $meta['columns']
            );

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
        foreach (self::INDEXES as $name => $meta) {
            if (! Schema::hasTable($meta['table']) || ! $this->indexExists($meta['table'], $name)) {
                continue;
            }

            DB::statement(sprintf('ALTER TABLE `%s` DROP INDEX `%s`', $meta['table'], $name));
        }
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
