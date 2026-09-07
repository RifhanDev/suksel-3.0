<?php

use App\Support\SchemaCompat;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        SchemaCompat::dropIfIncomplete('tender_teknikal_spesifikasi_evaluations');

        if (Schema::hasTable('tender_teknikal_spesifikasi_evaluations')) {
            return;
        }

        Schema::create('tender_teknikal_spesifikasi_evaluations', function (Blueprint $table) {
            $table->id();
            SchemaCompat::referenceColumn($table, 'tender_id', 'tenders');
            SchemaCompat::referenceColumn($table, 'vendor_id', 'vendors');
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
