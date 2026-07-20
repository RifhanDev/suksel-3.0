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
        Schema::create('technical_checklist_headers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('tender_id')->unique();
            $table->decimal('max_score', 10, 2)->default(0);
            $table->decimal('passing_score', 10, 2)->default(0);
            $table->decimal('passing_percentage', 5, 2)->default(0);
            $table->string('status', 50)->default('draft')->index();
            $table->timestamp('submitted_at')->nullable();
            $table->unsignedBigInteger('submitted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('tender_id', 'tch_tender_fk')
                ->references('id')
                ->on('tenders')
                ->cascadeOnDelete();

            $table->foreign('submitted_by', 'tch_submitted_by_fk')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->foreign('created_by', 'tch_created_by_fk')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->foreign('updated_by', 'tch_updated_by_fk')
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
        Schema::dropIfExists('technical_checklist_headers');
    }
};
