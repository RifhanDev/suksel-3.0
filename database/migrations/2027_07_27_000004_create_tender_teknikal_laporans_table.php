<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tender_teknikal_laporans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tender_id')->unique();
            // Bila Langkah 1 (Pematuhan) / Langkah 2 (Spesifikasi) disahkan hantar — boleh
            // null walaupun semua pembekal lulus (eliminateTidakLayak() singkir kosong dalam
            // kes itu, jadi bilangan disingkir bukan penanda dipercayai; ini penanda eksplisit).
            $table->timestamp('pematuhan_confirmed_at')->nullable();
            $table->timestamp('spesifikasi_confirmed_at')->nullable();
            // Peringkat 1 & 2 — catatan laporan ringkas setiap peringkat.
            $table->text('catatan_pematuhan')->nullable();
            $table->text('catatan_spesifikasi')->nullable();
            // Pengesyoran: perenggan pembuka + senarai justifikasi (bernombor auto di UI).
            $table->text('pengesyoran_intro')->nullable();
            $table->json('pengesyoran_justifikasi')->nullable();
            // Pembekal disyorkan (kedudukan #1 dalam rumusan Spesifikasi, jika ada).
            $table->unsignedBigInteger('winning_vendor_id')->nullable();
            $table->string('status', 50)->default('draft');
            $table->timestamp('submitted_at')->nullable();
            $table->unsignedBigInteger('submitted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('tender_id', 'ttl_tender_fk')
                ->references('id')->on('tenders')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_teknikal_laporans');
    }
};
