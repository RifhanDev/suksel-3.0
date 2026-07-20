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
        Schema::create('technical_checklist_files', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('technical_checklist_header_id')->nullable()->index();
            $table->unsignedBigInteger('technical_checklist_item_id')->nullable()->index();
            $table->string('file_type', 50)->default('support')->index();
            $table->string('original_name', 255);
            $table->string('stored_name', 255);
            $table->string('path', 500);
            $table->string('mime_type', 150)->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->timestamps();

            $table->foreign('technical_checklist_header_id', 'tcf_header_fk')
                ->references('id')
                ->on('technical_checklist_headers')
                ->cascadeOnDelete();

            $table->foreign('technical_checklist_item_id', 'tcf_item_fk')
                ->references('id')
                ->on('technical_checklist_items')
                ->cascadeOnDelete();

            $table->foreign('uploaded_by', 'tcf_uploaded_by_fk')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technical_checklist_files');
    }
};
