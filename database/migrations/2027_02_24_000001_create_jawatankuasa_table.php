<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJawatankuasaTable extends Migration
{
    public function up(): void
    {
        Schema::create('jawatankuasa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tender_id')->nullable();
            $table->enum('jenis_jawatankuasa', ['spec', 'open', 'tech', 'fin'])->index();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('catatan')->nullable();
            $table->string('dokumen_sokongan_nama')->nullable();
            $table->string('dokumen_sokongan_path')->nullable();
            $table->timestamp('dihantar_pemakluman_pada')->nullable();
            $table->timestamps();
            $table->foreign('tender_id')->references('id')->on('tenders')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jawatankuasa');
    }
}
