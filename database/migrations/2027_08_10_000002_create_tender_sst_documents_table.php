<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Only the extra documents a user uploads. The five system documents
        // (SST, Lampiran B/C, etc.) are generated on demand, so none are stored here.
        Schema::create('tender_sst_documents', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('tender_sst_id');

            // Free-text label the user types, shown as Kandungan.
            $table->string('document_name');
            $table->string('original_name')->nullable();
            $table->string('stored_name')->nullable();
            $table->string('path')->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->timestamps();

            $table->foreign('tender_sst_id', 'tender_sst_documents_sst_fk')
                ->references('id')->on('tender_ssts')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_sst_documents');
    }
};
