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
        Schema::create('tender_prestasi_kerjas', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('tender_id')->unique()->constrained('tenders')->cascadeOnDelete();
            $table->string('status', 50)->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('tender_prestasi_kerja_items', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('tender_prestasi_kerja_id')->constrained('tender_prestasi_kerjas')->cascadeOnDelete();
            $table->text('nama');
            $table->string('no_kontrak', 255)->nullable();
            $table->decimal('harga', 15, 2)->default(0.00);
            $table->string('tarikh_tapak', 100)->nullable();
            $table->integer('tempoh')->nullable();
            $table->string('tarikh_siap', 100)->nullable();
            $table->string('tarikh_penilaian', 100)->nullable();
            $table->string('luputan', 100)->nullable();
            $table->decimal('kemajuan_sebenar', 5, 2)->nullable();
            $table->decimal('kemajuan_jadual', 5, 2)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('tender_prestasi_kerja_dokumens', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('tender_prestasi_kerja_id')->constrained('tender_prestasi_kerjas')->cascadeOnDelete();
            $table->string('original_name', 500);
            $table->string('stored_name', 500);
            $table->string('path', 500);
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tender_prestasi_kerja_dokumens');
        Schema::dropIfExists('tender_prestasi_kerja_items');
        Schema::dropIfExists('tender_prestasi_kerjas');
    }
};
