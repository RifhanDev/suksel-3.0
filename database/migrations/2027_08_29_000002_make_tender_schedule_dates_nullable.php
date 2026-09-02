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

        $this->setNullable('tenders', self::COLUMNS, true);
    }

    public function down(): void
    {
        if (! Schema::hasTable('tenders')) {
            return;
        }

        // Hanya kembalikan NOT NULL bagi lajur yang benar-benar tiada NULL.
        // Selepas aliran 3.0 berjalan, lajur ini memang mengandungi NULL, dan
        // memaksa NOT NULL ke atasnya akan menggagalkan rollback di tengah
        // jalan — meninggalkan skema separuh berubah.
        $safe = array_values(array_filter(
            self::COLUMNS,
            fn (string $column) => ! DB::table('tenders')->whereNull($column)->exists()
        ));

        $this->setNullable('tenders', $safe, false);
    }

    /**
     * Mengubah kebolehan NULL beberapa lajur dalam SATU pernyataan ALTER.
     *
     * Menukar kebolehan NULL memaksa InnoDB membina semula seluruh jadual.
     * Mengeluarkan satu ALTER bagi setiap lajur bermakna lima pembinaan semula
     * `tenders` berturut-turut; menggabungkannya menjadikan hanya satu. Pada
     * jadual produksi perbezaan itu ialah beberapa minit lawan berpuluh minit.
     *
     * Menggunakan MODIFY dengan COLUMN_TYPE sebenar yang dibaca daripada
     * information_schema, bukan ->change(). Blueprint->change() menuntut
     * takrifan lajur ditulis semula sepenuhnya dan menggugurkan apa-apa atribut
     * yang tertinggal — berbahaya pada jadual warisan yang takrifan sebenarnya
     * tidak diketahui repo ini.
     *
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

            // MODIFY menulis semula takrifan lajur sepenuhnya, jadi DEFAULT dan
            // atribut seperti ON UPDATE CURRENT_TIMESTAMP hilang melainkan
            // dinyatakan semula.
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

        // INPLACE dengan LOCK=NONE membiarkan bacaan dan tulisan diteruskan
        // sepanjang pembinaan semula, jadi tapak kekal berfungsi semasa deploy.
        // Tidak semua versi atau varian server menyokongnya untuk perubahan ini,
        // jadi gagal-lembut kepada ALTER biasa dan bukannya menggagalkan deploy.
        try {
            DB::statement($sql . ', ALGORITHM=INPLACE, LOCK=NONE');
        } catch (\Throwable $e) {
            DB::statement($sql);
        }
    }

    private function isExpressionDefault(string $default): bool
    {
        return (bool) preg_match('/^(CURRENT_TIMESTAMP|NOW\(\)|CURRENT_DATE)/i', $default);
    }
};
