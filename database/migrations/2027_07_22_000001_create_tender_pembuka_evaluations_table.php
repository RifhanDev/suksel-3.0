<?php

use App\Support\SchemaCompat;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Pembuka checklist evaluations per tender/vendor/item.
 *
 * Error 3780 happens when `tender_id` width does not match `tenders.id`
 * (INT on restored 2.0 DBs, BIGINT on fresh migrates). Same for vendors.id.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Only drops if table is empty AND has no foreign keys — leftover from a
        // failed FK step after CREATE. Never drops tables that have data or FKs.
        SchemaCompat::dropIfIncomplete('tender_pembuka_evaluations');

        if (Schema::hasTable('tender_pembuka_evaluations')) {
            return;
        }

        Schema::create('tender_pembuka_evaluations', function (Blueprint $table) {
            $table->id();
            SchemaCompat::referenceColumn($table, 'tender_id', 'tenders');
            SchemaCompat::referenceColumn($table, 'vendor_id', 'vendors');
            $table->uuid('checklist_item_uuid')->index();
            // 1 = Ada (Passed), 0 = Tiada (Failed)
            $table->tinyInteger('status_pematuhan')->default(1);
            // Required when status_pematuhan = 0
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->unique(
                ['tender_id', 'vendor_id', 'checklist_item_uuid'],
                'tpe_tender_vendor_item_unique'
            );

            $table->foreign('tender_id', 'tpe_tender_fk')
                ->references('id')
                ->on('tenders')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_pembuka_evaluations');
    }
};
