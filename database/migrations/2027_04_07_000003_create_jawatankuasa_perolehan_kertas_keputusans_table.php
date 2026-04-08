<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('jawatankuasa_perolehan_kertas_keputusans')) {
            return;
        }

        Schema::create('jawatankuasa_perolehan_kertas_keputusans', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tender_id')->unique();
            $table->boolean('dengan_syarat')->nullable();
            $table->text('syarat_nyatakan')->nullable();
            $table->text('pengesyoran_catatan')->nullable();
            $table->string('justifikasi_pemilihan_pembekal', 255)->nullable();
            $table->string('lampiran_file_nama')->nullable();
            $table->string('lampiran_file_path')->nullable();
            $table->enum('keputusan', ['Lulus', 'Tawaran Semula', 'Batal', 'Tangguh'])->nullable();
            $table->text('catatan')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->foreign('tender_id')->references('id')->on('tenders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawatankuasa_perolehan_kertas_keputusans');
    }
};
