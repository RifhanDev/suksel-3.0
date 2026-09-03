<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Melonggarkan NOT NULL pada lajur teks warisan yang aliran 3.0 tidak pernah isi.
 *
 * `submission_location_address` dan `tender_rules` tidak muncul dalam borang
 * cipta tender mahupun dalam TendersController — kedua-duanya medan 2.0 yang
 * diisi pada peringkat kemudian, atau tidak langsung. Pada pangkalan data yang
 * dipulihkan daripada dump 2.0 ia kekal NOT NULL, jadi setiap percubaan cipta
 * tender gagal dengan SQLSTATE[23000] 1048.
 *
 * Ini sambungan kepada 2027_08_29_000002, yang melonggarkan lima lajur tarikh
 * atas sebab yang sama. Kedua-duanya dibuat serentak di sini supaya
 * tender_rules tidak menjadi kegagalan seterusnya sebaik yang pertama dibaiki.
 *
 * Lajur NOT NULL lain pada `tenders` sengaja TIDAK disentuh: name, ref_number,
 * creator_id, organization_unit_id dan price semuanya memang dibekalkan oleh
 * aliran cipta tender, jadi kekangannya betul dan berguna.
 *
 * Kedua-duanya jenis TEXT, yang dalam MySQL tidak boleh mempunyai DEFAULT.
 * Melonggarkan NOT NULL tidak membuang data dan tidak menjejaskan baris sedia
 * ada, jadi ia selamat di produksi.
 */
return new class extends Migration
{
    private const COLUMNS = [
        'submission_location_address',
        'tender_rules',
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

        // Jangan menunggu metadata lock tanpa had. ALTER yang tersekat turut
        // menyekat SETIAP pertanyaan lain pada jadual yang sama, jadi deploy
        // yang tergantung bererti tapak tergantung. Gagal cepat dengan ralat
        // yang jelas jauh lebih baik daripada menggantung senyap berjam-jam.
        DB::statement('SET SESSION lock_wait_timeout = 60');

        // INPLACE dengan LOCK=NONE membiarkan bacaan dan tulisan diteruskan
        // sepanjang pembinaan semula, jadi tapak kekal berfungsi semasa deploy.
        // Tidak semua versi atau varian server menyokongnya untuk perubahan ini,
        // jadi gagal-lembut kepada ALTER biasa dan bukannya menggagalkan deploy.
        try {
            DB::statement($sql . ', ALGORITHM=INPLACE, LOCK=NONE');
        } catch (\Throwable $e) {
            // Tamat masa kunci bukan tanda INPLACE tidak disokong — mencuba
            // semula hanya menunggu 60 saat lagi untuk kunci yang sama. Lepaskan
            // ralat itu supaya deploy berhenti dengan sebab yang jelas.
            if ($this->isLockTimeout($e)) {
                throw $e;
            }

            DB::statement($sql);
        }
    }

    /**
     * MySQL 1205 = Lock wait timeout exceeded, 3572 = Statement aborted because
     * it was waiting for a metadata lock.
     */
    private function isLockTimeout(\Throwable $e): bool
    {
        return (bool) preg_match('/(1205|3572)|lock wait timeout|metadata lock/i', $e->getMessage());
    }

    private function isExpressionDefault(string $default): bool
    {
        return (bool) preg_match('/^(CURRENT_TIMESTAMP|NOW\(\)|CURRENT_DATE)/i', $default);
    }
};
