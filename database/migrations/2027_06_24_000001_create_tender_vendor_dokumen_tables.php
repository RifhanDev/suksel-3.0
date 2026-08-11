<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tender_vendor_dokumen_responses', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedInteger('tender_id')->index();
            $table->unsignedBigInteger('vendor_id')->index();
            $table->uuid('checklist_item_uuid')->index();
            $table->string('section', 50)->index();
            $table->string('response_type', 30)->index()->comment('key_in');
            $table->json('payload')->nullable();
            $table->string('status', 30)->default('draft')->index();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->unique(
                ['tender_id', 'vendor_id', 'checklist_item_uuid'],
                'tvd_resp_tender_vendor_item_unique'
            );

            $table->foreign('tender_id', 'tvd_resp_tender_fk')
                ->references('id')
                ->on('tenders')
                ->cascadeOnDelete();
        });

        Schema::create('tender_vendor_dokumen_files', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedInteger('tender_id')->index();
            $table->unsignedBigInteger('vendor_id')->index();
            $table->uuid('checklist_item_uuid')->index();
            $table->string('section', 50)->index();
            $table->string('original_name', 500);
            $table->string('stored_name', 500);
            $table->string('path', 500);
            $table->string('mime_type', 150)->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->timestamps();

            $table->index(['tender_id', 'vendor_id', 'checklist_item_uuid'], 'tvd_files_lookup_idx');

            $table->foreign('tender_id', 'tvd_files_tender_fk')
                ->references('id')
                ->on('tenders')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_vendor_dokumen_files');
        Schema::dropIfExists('tender_vendor_dokumen_responses');
    }
};
