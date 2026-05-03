<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profil_petenders', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('tender_id')->unique()->constrained('tenders')->cascadeOnDelete();
            $table->string('nama_syarikat', 255)->nullable();
            $table->string('jenis_syarikat', 50)->nullable();
            $table->string('taraf_petender', 50)->nullable();
            $table->text('alamat')->nullable();
            $table->string('pegawai_nama', 255)->nullable();
            $table->string('pegawai_telefon', 50)->nullable();
            $table->string('pegawai_emel', 255)->nullable();
            $table->string('no_ssm', 100)->nullable();
            $table->string('no_mof', 100)->nullable();
            $table->string('tempoh_sah_mof', 50)->nullable();
            $table->unsignedInteger('bil_pekerja')->default(0);
            $table->unsignedInteger('bil_pekerja_teknikal')->default(0);
            $table->decimal('modal_berbayar', 15, 2)->default(0);
            $table->decimal('modal_dibenarkan', 15, 2)->default(0);
            $table->json('aset')->nullable();
            $table->json('peralatan')->nullable();
            $table->decimal('tanggungan', 15, 2)->default(0);
            $table->decimal('baki_wang_bank', 15, 2)->default(0);
            $table->string('jenis_skor_modal_berbayar', 50)->nullable();
            $table->string('jenis_skor_modal_dibenarkan', 50)->nullable();
            $table->string('status', 50)->default('draft')->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profil_petenders');
    }
};
