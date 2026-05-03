<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penyata_banks', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('tender_id')->unique()->constrained('tenders')->cascadeOnDelete();
            $table->unsignedTinyInteger('dari_bulan')->nullable();
            $table->unsignedSmallInteger('dari_tahun')->nullable();
            $table->unsignedTinyInteger('hingga_bulan')->nullable();
            $table->unsignedSmallInteger('hingga_tahun')->nullable();
            $table->decimal('jumlah_keseluruhan', 15, 2)->default(0);
            $table->decimal('purata', 15, 2)->default(0);
            $table->string('jenis_skor_purata', 50)->nullable();
            $table->string('status', 50)->default('draft')->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penyata_banks');
    }
};
