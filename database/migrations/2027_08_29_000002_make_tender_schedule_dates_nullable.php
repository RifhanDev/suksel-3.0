<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Melonggarkan NOT NULL pada lajur jadual tender yang aliran 3.0 biarkan kosong
 * semasa penciptaan.
 *
 * Dalam 2.0 tender dicipta serentak dengan tarikh pengiklanannya, jadi lajur ini
 * NOT NULL. Dalam 3.0 tender dicipta dahulu dan tarikh diisi kemudian pada
 * peringkat Penyediaan Iklan — TendersController malah menetapkannya kepada null
 * secara eksplisit sebaik sahaja backend memulangkan tender_id. Pada pangkalan
 * data warisan itu gagal dengan SQLSTATE[23000] 1048.
 *
 * create_tender_table mengisytiharkan lajur ini nullable, tetapi ia melangkau
 * penciptaan apabila jadual sudah wujud, dan tiada migration ALTER pernah wujud.
 * Jadi pada setiap pangkalan data yang dipulihkan daripada 2.0 — staging dan
 * produksi — lajur ini kekal NOT NULL tidak kira berapa kali migrate dijalankan.
 * Inilah migration yang merapatkan jurang tersebut.
 *
 * Melonggarkan NOT NULL tidak membuang data dan tidak menjejaskan baris sedia
 * ada, jadi ia selamat di produksi.
 */
return new class extends Migration
{
    private const COLUMNS = [
        'advertise_start_date',
        'advertise_stop_date',
        'document_start_date',
        'document_stop_date',
        'submission_datetime',
    ];

    public function up(): void
    {
        if (! Schema::hasTable('tenders')) {
            return;
        }

        foreach (self::COLUMNS as $column) {
            $this->setNullable('tenders', $column, true);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('tenders')) {
            return;
        }

        foreach (self::COLUMNS as $column) {
            // Hanya kembalikan NOT NULL apabila lajur benar-benar tiada NULL.
            // Selepas aliran 3.0 berjalan, lajur ini memang mengandungi NULL,
            // dan memaksa NOT NULL ke atasnya akan menggagalkan rollback di
            // tengah jalan — meninggalkan skema separuh berubah.
            if (DB::table('tenders')->whereNull($column)->exists()) {
                continue;
            }

            $this->setNullable('tenders', $column, false);
        }
    }

    /**
     * Mengubah kebolehan NULL sesuatu lajur tanpa menyentuh jenisnya.
     *
     * Menggunakan MODIFY dengan COLUMN_TYPE sebenar yang dibaca daripada
     * information_schema, bukan ->change(). Blueprint->change() memerlukan
     * takrifan lajur ditulis semula sepenuhnya dan akan menggugurkan apa-apa
     * atribut yang tidak dinyatakan semula — berbahaya pada jadual warisan yang
     * takrifan sebenarnya tidak diketahui repo ini.
     */
    private function setNullable(string $table, string $column, bool $nullable): void
    {
        $info = DB::selectOne(
            'SELECT COLUMN_TYPE AS column_type, IS_NULLABLE AS is_nullable,
                    COLUMN_DEFAULT AS column_default, EXTRA AS extra
               FROM information_schema.COLUMNS
              WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = ?
                AND COLUMN_NAME = ?',
            [$table, $column]
        );

        if (! $info) {
            return;
        }

        $currentlyNullable = strtoupper($info->is_nullable) === 'YES';

        if ($currentlyNullable === $nullable) {
            return;
        }

        $sql = sprintf(
            'ALTER TABLE `%s` MODIFY `%s` %s %s',
            $table,
            $column,
            $info->column_type,
            $nullable ? 'NULL' : 'NOT NULL'
        );

        // MODIFY menulis semula takrifan lajur sepenuhnya, jadi DEFAULT dan
        // atribut seperti ON UPDATE CURRENT_TIMESTAMP hilang melainkan
        // dinyatakan semula.
        if ($info->column_default !== null) {
            $sql .= $this->isExpressionDefault($info->column_default)
                ? ' DEFAULT ' . $info->column_default
                : ' DEFAULT ' . DB::getPdo()->quote($info->column_default);
        }

        if (! empty($info->extra) && stripos($info->extra, 'on update') !== false) {
            $sql .= ' ' . $info->extra;
        }

        DB::statement($sql);
    }

    private function isExpressionDefault(string $default): bool
    {
        return (bool) preg_match('/^(CURRENT_TIMESTAMP|NOW\(\)|CURRENT_DATE)/i', $default);
    }
};
