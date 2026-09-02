<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Lebar lajur rujukan tidak boleh dikodkan tegar: MySQL menolak foreign key
     * yang lebar/tandanya tidak sepadan (ralat 3780).
     *
     * `tenders.id` berbeza mengikut environment. create_tender_table melangkau
     * CREATE apabila jadual sudah wujud, jadi pangkalan data yang dipulihkan
     * daripada backup 2.0 (staging dan produksi) mengekalkan INT UNSIGNED
     * warisan, manakala pangkalan data yang benar-benar baharu mendapat
     * BIGINT UNSIGNED daripada $table->id(). Baca jenis sebenar dan padankan.
     */
    private function matchesReference(Blueprint $table, string $column, string $referenceTable): void
    {
        $type = DB::selectOne(
            'SELECT DATA_TYPE AS data_type
               FROM information_schema.COLUMNS
              WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = ?
                AND COLUMN_NAME = ?',
            [$referenceTable, 'id']
        );

        if (strtolower($type->data_type ?? 'bigint') === 'int') {
            $table->unsignedInteger($column)->index();

            return;
        }

        $table->unsignedBigInteger($column)->index();
    }

    private function hasForeignKeys(string $table): bool
    {
        $result = DB::selectOne(
            "SELECT COUNT(*) AS total
               FROM information_schema.TABLE_CONSTRAINTS
              WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = ?
                AND CONSTRAINT_TYPE = 'FOREIGN KEY'",
            [$table]
        );

        return (int) ($result->total ?? 0) > 0;
    }

    public function up(): void
    {
        Schema::table('tender_vendors', function (Blueprint $table) {
            if (! Schema::hasColumn('tender_vendors', 'surat_niat_diperlukan')) {
                $table->boolean('surat_niat_diperlukan')->nullable()->default(true);
            }
            if (! Schema::hasColumn('tender_vendors', 'surat_niat_catatan')) {
                $table->text('surat_niat_catatan')->nullable();
            }
        });

        // Larian yang gagal separuh jalan boleh meninggalkan `surat_niats` tanpa
        // foreign key: MySQL mencipta jadual dahulu, kemudian ALTER untuk setiap
        // constraint — dan ALTER itulah yang gagal dengan ralat 3780. Guard
        // "sudah wujud" semata-mata akan melangkau jadual cacat itu lalu menanda
        // migration ini selesai. Buang tinggalan tersebut, tetapi hanya apabila
        // ia benar-benar kosong dan memang tiada constraint.
        if (Schema::hasTable('surat_niats')) {
            if ($this->hasForeignKeys('surat_niats') || DB::table('surat_niats')->exists()) {
                return;
            }

            Schema::drop('surat_niats');
        }

        Schema::create('surat_niats', function (Blueprint $table) {
            $table->id();
            $this->matchesReference($table, 'tender_id', 'tenders');
            $this->matchesReference($table, 'pembekal_id', 'tender_vendors');
            $table->string('no_loa')->unique();
            $table->string('jenis')->default('Surat Niat');
            $table->enum('tujuan', ['perbincangan', 'rundingan'])->default('perbincangan');
            $table->json('faktor')->nullable();
            $table->text('faktor_lain')->nullable();
            $table->unsignedInteger('tempoh_maklumbalas_hari');
            $table->enum('status', ['draf', 'dihantar'])->default('draf');
            $table->string('file_path')->nullable();
            $table->unsignedBigInteger('generated_by')->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();

            $table->foreign('tender_id')->references('id')->on('tenders')->onDelete('cascade');
            $table->foreign('pembekal_id')->references('id')->on('tender_vendors')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_niats');

        Schema::table('tender_vendors', function (Blueprint $table) {
            $table->dropColumn(['surat_niat_diperlukan', 'surat_niat_catatan']);
        });
    }
};
