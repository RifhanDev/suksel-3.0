<?php

use App\Support\SchemaCompat;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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

        // Only drops empty leftover tables with no FKs (failed half-run).
        SchemaCompat::dropIfIncomplete('surat_niats');

        if (Schema::hasTable('surat_niats')) {
            return;
        }

        Schema::create('surat_niats', function (Blueprint $table) {
            $table->id();
            SchemaCompat::referenceColumn($table, 'tender_id', 'tenders');
            SchemaCompat::referenceColumn($table, 'pembekal_id', 'tender_vendors');
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
