<?php

namespace App\Support;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Pembantu skema untuk pangkalan data yang dipulihkan daripada dump 2.0.
 *
 * Jadual warisan dalam `etender` menggunakan increments() — `tenders.id`,
 * `vendors.id` dan `users.id` semuanya INT UNSIGNED — sedangkan jadual yang
 * dicipta oleh migration 3.0 menggunakan id() dan mendapat BIGINT UNSIGNED.
 *
 * MySQL menolak foreign key yang lebar atau tandanya tidak sepadan dengan lajur
 * yang dirujuk (ralat 3780). Mengekod unsignedBigInteger() secara tegar berjaya
 * pada pangkalan data pembangunan yang dibina dari kosong, dan gagal pada setiap
 * sasaran deploy sebenar.
 */
class SchemaCompat
{
    /**
     * Menambah lajur rujukan yang lebarnya sepadan dengan `id` jadual sasaran.
     */
    public static function referenceColumn(
        Blueprint $table,
        string $column,
        string $referencedTable,
        bool $nullable = false
    ): void {
        $definition = self::idType($referencedTable) === 'int'
            ? $table->unsignedInteger($column)
            : $table->unsignedBigInteger($column);

        if ($nullable) {
            $definition->nullable();
        }

        $definition->index();
    }

    /**
     * Jenis `id` jadual, dibaca pada masa jalan. Lalai kepada bigint apabila
     * jadual belum wujud — pada larian bersih ia akan dicipta dengan id().
     */
    public static function idType(string $table): string
    {
        $result = DB::selectOne(
            'SELECT DATA_TYPE AS data_type
               FROM information_schema.COLUMNS
              WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = ?
                AND COLUMN_NAME = ?',
            [$table, 'id']
        );

        return strtolower($result->data_type ?? 'bigint');
    }

    /**
     * Membuang jadual yang tertinggal daripada larian yang gagal separuh jalan.
     *
     * MySQL mencipta jadual dahulu, kemudian menambah setiap foreign key sebagai
     * ALTER berasingan. Apabila ALTER itu gagal, jadual kekal wujud tanpa
     * constraint dan tanpa baris migration direkodkan — lalu guard
     * "sudah wujud" pada larian berikutnya akan melangkaunya dan menanda
     * migration selesai atas skema yang cacat.
     *
     * Hanya membuang apabila jadual benar-benar kosong dan memang tiada
     * foreign key.
     */
    public static function dropIfIncomplete(string $table): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        $constraints = DB::selectOne(
            "SELECT COUNT(*) AS total
               FROM information_schema.TABLE_CONSTRAINTS
              WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = ?
                AND CONSTRAINT_TYPE = 'FOREIGN KEY'",
            [$table]
        );

        if ((int) ($constraints->total ?? 0) > 0 || DB::table($table)->exists()) {
            return;
        }

        Schema::drop($table);
    }
}
