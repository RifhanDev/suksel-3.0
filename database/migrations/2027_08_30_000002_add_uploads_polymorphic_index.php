<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Menambah indeks (uploadable_type, uploadable_id) pada `uploads`.
 *
 * create_uploads_table mengisytiharkan indeks ini, tetapi ia melangkau
 * penciptaan apabila jadual sudah wujud. Pada pangkalan data yang dipulihkan
 * daripada dump 2.0 — staging dan produksi — jadual itu diwarisi tanpa indeks,
 * dan tiada migration pernah menambahnya.
 *
 * Kesannya bukan kecil. Setiap paparan banner menjalankan
 * "select * from uploads where uploadable_type = 'App\Banner'", yang tanpa
 * indeks mengimbas keseluruhan jadual. Di staging itu 549,241 baris, mengambil
 * lapan hingga sepuluh minit setiap satu. Muat halaman baharu mencetuskan
 * imbasan baharu sebelum yang sebelumnya habis, sehingga Threads_running
 * mencecah 28 dan pertanyaan seremeh SELECT 1 pun menunggu 446ms. Itulah punca
 * sebenar 504 Gateway Time-out di staging.
 */
return new class extends Migration
{
    private const TABLE = 'uploads';
    private const NAME  = 'uploads_uploadable_type_uploadable_id_index';

    public function up(): void
    {
        if (! Schema::hasTable(self::TABLE) || $this->indexExists()) {
            return;
        }

        // Jangan menunggu metadata lock tanpa had: DDL yang tersekat turut
        // menyekat setiap pertanyaan lain pada jadual ini.
        DB::statement('SET SESSION lock_wait_timeout = 60');

        $sql = sprintf(
            'ALTER TABLE `%s` ADD INDEX `%s` (`uploadable_type`, `uploadable_id`)',
            self::TABLE,
            self::NAME
        );

        // INPLACE dengan LOCK=NONE membenarkan bacaan dan tulisan diteruskan
        // semasa indeks dibina — penting kerana server ini sedang sesak.
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
        if (! Schema::hasTable(self::TABLE) || ! $this->indexExists()) {
            return;
        }

        DB::statement(sprintf('ALTER TABLE `%s` DROP INDEX `%s`', self::TABLE, self::NAME));
    }

    private function indexExists(): bool
    {
        $result = DB::selectOne(
            'SELECT COUNT(*) AS total
               FROM information_schema.STATISTICS
              WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = ?
                AND INDEX_NAME = ?',
            [self::TABLE, self::NAME]
        );

        return (int) ($result->total ?? 0) > 0;
    }

    private function isLockTimeout(\Throwable $e): bool
    {
        return (bool) preg_match('/\b(1205|3572)\b|lock wait timeout|metadata lock/i', $e->getMessage());
    }
};
