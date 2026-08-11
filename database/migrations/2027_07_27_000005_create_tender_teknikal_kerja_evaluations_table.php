<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tender_teknikal_kerja_evaluations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tender_id')->index('ttke_tender_idx');
            $table->unsignedBigInteger('vendor_id')->index('ttke_vendor_idx');
            // 'lulus' | 'tidak_lulus'
            $table->string('status', 20);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->unique(['tender_id', 'vendor_id'], 'ttke_tender_vendor_unique');

            $table->foreign('tender_id', 'ttke_tender_fk')
                ->references('id')->on('tenders')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_teknikal_kerja_evaluations');
    }
};
