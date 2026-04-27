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
        Schema::create('technical_checklist_items', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('technical_checklist_header_id')->index();
            $table->string('source_type', 50)->default('manual')->index();
            $table->text('title');
            $table->string('mechanism', 50)->nullable()->index();
            $table->string('vendor_action', 50)->nullable();
            $table->decimal('score', 10, 2)->default(0);
            $table->string('status', 50)->default('draft')->index();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->unsignedBigInteger('standard_item_id')->nullable()->index();
            $table->unsignedBigInteger('specification_document_id')->nullable()->index();
            $table->string('online_form_key', 100)->nullable()->index();
            $table->string('online_form_route', 255)->nullable();
            $table->timestamps();

            $table->foreign('technical_checklist_header_id', 'tci_header_fk')
                ->references('id')
                ->on('technical_checklist_headers')
                ->cascadeOnDelete();

            $table->foreign('standard_item_id', 'tci_standard_fk')
                ->references('id')
                ->on('standard_checklist_items')
                ->nullOnDelete();

            $table->foreign('specification_document_id', 'tci_spec_doc_fk')
                ->references('id')
                ->on('technical_specification_documents')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technical_checklist_items');
    }
};
