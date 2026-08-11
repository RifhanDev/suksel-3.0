<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tender_teknikal_spesifikasi_evaluations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tender_id')->index('ttse_tender_idx');
            $table->unsignedBigInteger('vendor_id')->index('ttse_vendor_idx');
            $table->uuid('checklist_item_uuid')->index('ttse_item_idx');
            $table->uuid('specification_detail_uuid')->index('ttse_detail_idx');
            // Raw entry as given: numeric string (text/number/yes_no+manual) or "yes"/"no" (yes_no+auto)
            $table->string('input_value', 50)->nullable();
            // Populated only for response_type=number and yes_no+score_mode=auto
            $table->decimal('skor_automatik', 10, 2)->nullable();
            // Populated only for response_type=text and yes_no+score_mode=manual
            $table->decimal('skor_manual', 10, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->unique(
                ['tender_id', 'vendor_id', 'specification_detail_uuid'],
                'ttse_tender_vendor_detail_unique'
            );
            $table->index(
                ['tender_id', 'vendor_id', 'checklist_item_uuid'],
                'ttse_tender_vendor_item_idx'
            );

            $table->foreign('tender_id', 'ttse_tender_fk')
                ->references('id')->on('tenders')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_teknikal_spesifikasi_evaluations');
    }
};
