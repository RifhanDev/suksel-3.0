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
        Schema::create('financial_checklist_headers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedInteger('tender_id')->unique();
            $table->foreign('tender_id')->references('id')->on('tenders')->cascadeOnDelete();
            $table->decimal('max_score', 10, 2)->default(0);
            $table->decimal('passing_score', 10, 2)->default(0);
            $table->decimal('passing_percentage', 5, 2)->default(0);
            $table->string('status', 50)->default('draft')->index();
            $table->timestamp('submitted_at')->nullable();
            $table->unsignedInteger('submitted_by')->nullable();
            $table->foreign('submitted_by')->references('id')->on('users')->nullOnDelete();
            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_checklist_headers');
    }
};
