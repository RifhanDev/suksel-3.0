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
        Schema::create('financial_checklist_items', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('financial_checklist_header_id')->index()->constrained('financial_checklist_headers')->cascadeOnDelete();
            $table->string('source_type', 50)->default('specification_document')->index();
            $table->foreignId('technical_item_id')->nullable()->index()->constrained('technical_checklist_items')->nullOnDelete();
            $table->foreignId('standard_item_id')->nullable()->index()->constrained('standard_checklist_items')->nullOnDelete();
            $table->text('title');
            $table->string('mechanism', 50)->nullable()->index();
            $table->string('vendor_action', 50)->nullable();
            $table->decimal('score', 10, 2)->default(0);
            $table->string('status', 50)->default('draft')->index();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_checklist_items');
    }
};
