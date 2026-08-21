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
        if (Schema::hasTable('jawatankuasas')) {
            return;
        }
        Schema::create('jawatankuasas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tender_id')->nullable();
            $table->enum('jenis_jawatankuasa', ['spec', 'open', 'tech', 'fin'])->index();
            $table->enum('p_p', ['1', '0'])->index();
            $table->enum('peranan', ['1', '2', '3'])->index();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('catatan')->nullable();
            $table->date('tarikh_mesyuarat')->nullable();
            $table->string('masa_mesyuarat', 10)->nullable();
            $table->string('lokasi_mesyuarat')->nullable();
            $table->string('dokumen_sokongan_nama')->nullable();
            $table->string('dokumen_sokongan_path')->nullable();
            $table->timestamp('dihantar_pemakluman_pada')->nullable();
            $table->timestamps();
            $table->foreign('tender_id')->references('id')->on('tenders')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawatankuasas');
    }
};
