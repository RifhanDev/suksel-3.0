<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Indeks untuk dua pertanyaan yang dijalankan oleh cron rekonsiliasi FPX.
 *
 * TransactionsController::queue_fpx_requery() dipanggil setiap minit oleh cron
 * dan menjalankan:
 *
 *   select count(*) ... where status = 'pending' and fpx_job_status = 1
 *   select * ... where method = 'fpx' and status = 'pending'
 *                and gateway_id is not null order by created_at asc limit ?
 *
 * `transactions` mengandungi lebih sejuta baris dan tiada indeks yang bermula
 * dengan `status` atau `method` — indeks sedia ada bermula dengan `id`,
 * `vendor_id`, `gateway_id` atau `user_id`. Kedua-dua pertanyaan itu mengimbas
 * hampir keseluruhan jadual, dan yang kedua menambah filesort untuk ORDER BY.
 *
 * Setiap larian mengambil lebih seminit sedangkan cron mencetuskannya setiap
 * minit, jadi larian bertimbun — tujuh salinan serentak diperhatikan di staging,
 * cukup untuk menepukan pangkalan data dan menyebabkan 504 pada halaman lain.
 * Sama seperti imbasan `uploads` sebelum ini.
 *
 * `created_at` diletakkan terakhir supaya indeks yang sama melayan penapisan
 * dan penyusunan, menghapuskan filesort.
 */
return new class extends Migration
{
    private const TABLE = 'transactions';

    private const INDEXES = [
        'transactions_fpx_pending_idx'    => ['status', 'method', 'created_at'],
        'transactions_fpx_job_status_idx' => ['status', 'fpx_job_status'],
    ];

    public function up(): void
    {
        if (! Schema::hasTable(self::TABLE)) {
            return;
        }

        $clauses = [];

        foreach (self::INDEXES as $name => $columns) {
            if ($this->indexExists($name) || ! $this->columnsExist($columns)) {
                continue;
            }

            $clauses[] = sprintf(
                'ADD INDEX `%s` (%s)',
                $name,
                implode(', ', array_map(fn ($c) => "`{$c}`", $columns))
            );
        }

        if ($clauses === []) {
            return;
        }

        // Jangan menunggu metadata lock tanpa had: DDL yang tersekat turut
        // menyekat setiap pertanyaan lain pada jadual ini.
        DB::statement('SET SESSION lock_wait_timeout = 60');

        // Kedua-dua indeks dalam satu ALTER: satu pembinaan semula, bukan dua.
        // LOCK=NONE membiarkan bacaan dan tulisan diteruskan sepanjang proses.
        $sql = sprintf('ALTER TABLE `%s` %s', self::TABLE, implode(', ', $clauses));

        try {
            DB::statement($sql . ', ALGORITHM=INPLACE, LOCK=NONE');
        } catch (\Throwable $e) {
            if ($this->isLockTimeout($e)) {
                throw $e;
            }

            DB::statement($sql);
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
        $result = DB::selectOne(
            'SELECT COUNT(*) AS total
               FROM information_schema.STATISTICS
              WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND INDEX_NAME = ?',
            [self::TABLE, $name]
        );

        return (int) ($result->total ?? 0) > 0;
    }

    /** @param list<string> $columns */
    private function columnsExist(array $columns): bool
    {
        foreach ($columns as $column) {
            if (! Schema::hasColumn(self::TABLE, $column)) {
                return false;
            }
        }

        return true;
    }

    private function isLockTimeout(\Throwable $e): bool
    {
        return (bool) preg_match('/\b(1205|3572)\b|lock wait timeout|metadata lock/i', $e->getMessage());
    }
};
