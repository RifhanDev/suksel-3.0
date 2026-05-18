<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ebidding_jadual_bidaans')) {
            return;
        }

        Schema::create('ebidding_jadual_bidaans', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tender_id')->unique();
            $table->date('tarikh_bidaan_mula')->nullable();
            $table->time('masa_bidaan_mula')->nullable();
            $table->date('tarikh_bidaan_tamat')->nullable();
            $table->time('masa_bidaan_tamat')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->foreign('tender_id')->references('id')->on('tenders')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ebidding_jadual_bidaans');
    }
};
