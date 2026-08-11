<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tender_teknikal_borang_evaluations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tender_id')->index('ttbe_tender_idx');
            $table->unsignedBigInteger('vendor_id')->index('ttbe_vendor_idx');
            $table->uuid('checklist_item_uuid')->index('ttbe_item_idx');
            // Dihadkan pada skema markah (technical_checklist_items.score) semasa disimpan.
            $table->decimal('skor_manual', 10, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->unique(
                ['tender_id', 'vendor_id', 'checklist_item_uuid'],
                'ttbe_tender_vendor_item_unique'
            );

            $table->foreign('tender_id', 'ttbe_tender_fk')
                ->references('id')->on('tenders')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_teknikal_borang_evaluations');
    }
};
