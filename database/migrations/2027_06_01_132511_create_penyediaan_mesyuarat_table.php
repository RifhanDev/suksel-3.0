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
        if (Schema::hasTable('penyediaan_mesyuarat')) {
            return;
        }

        Schema::create('penyediaan_mesyuarat', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tender_id')->index();
            $table->string('jenis_jawatankuasa', 20)->index();
            $table->date('tarikh_mesyuarat');
            $table->string('masa', 10);
            $table->string('tempat');
            $table->enum('status', ['Draf', 'Dihantar'])->default('Draf');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->foreign('tender_id')->references('id')->on('tenders')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyediaan_mesyuarat');
    }
};
