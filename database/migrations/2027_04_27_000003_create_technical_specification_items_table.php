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
        Schema::create('technical_specification_items', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('technical_specification_document_id');
            $table->text('title')->nullable();
            $table->decimal('quantity', 15, 4)->nullable();
            $table->string('unit', 50)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('technical_specification_document_id', 'tsi_document_idx');
            $table->foreign('technical_specification_document_id', 'tsi_document_fk')
                ->references('id')
                ->on('technical_specification_documents')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technical_specification_items');
    }
};
