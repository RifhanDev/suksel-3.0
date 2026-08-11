<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tender_kewangan_laporans', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tender_id')->unique();
            $table->text('catatan_peringkat1')->nullable();
            $table->text('catatan_peringkat2')->nullable();
            $table->text('catatan_peringkat3')->nullable();
            $table->json('pengesyoran_justifikasi')->nullable();
            $table->string('status', 50)->default('draft');
            $table->timestamp('submitted_at')->nullable();
            $table->unsignedInteger('submitted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('tender_id', 'tkl_tender_fk')
                ->references('id')->on('tenders')->cascadeOnDelete();

            $table->foreign('submitted_by', 'tkl_submitted_by_fk')
                ->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_kewangan_laporans');
    }
};
