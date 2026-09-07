<?php

use App\Support\SchemaCompat;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        SchemaCompat::dropIfIncomplete('tender_teknikal_borang_evaluations');

        if (Schema::hasTable('tender_teknikal_borang_evaluations')) {
            return;
        }

        Schema::create('tender_teknikal_borang_evaluations', function (Blueprint $table) {
            $table->id();
            SchemaCompat::referenceColumn($table, 'tender_id', 'tenders');
            SchemaCompat::referenceColumn($table, 'vendor_id', 'vendors');
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
