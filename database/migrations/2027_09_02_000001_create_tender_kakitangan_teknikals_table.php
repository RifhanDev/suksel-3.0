<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tender_kakitangan_teknikals', function (Blueprint $table) {
            $table->id();
            $table->string('uuid', 36)->unique();
            $table->string('tender_uuid')->index();
            $table->unsignedBigInteger('vendor_id')->nullable()->index();

            $table->string('nama_pegawai');
            $table->string('tahap_pendidikan');
            $table->integer('jumlah_pengalaman')->default(0);
            $table->text('sijil_professional')->nullable();
            $table->string('kategori', 20)->nullable()->index();

            $table->smallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_kakitangan_teknikals');
    }
};
