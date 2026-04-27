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
        Schema::create('technical_specification_documents', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('tender_id')->index();
            $table->text('title')->nullable();
            $table->string('item_type', 100)->nullable();
            $table->string('specification_type', 50)->default('technical')->index();
            $table->string('goods_type', 50)->nullable();
            $table->string('weighting_type', 50)->nullable();
            $table->boolean('physical_submission')->default(false);
            $table->string('status', 50)->default('draft')->index();
            $table->decimal('total_score', 10, 2)->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('tender_id', 'tsd_tender_fk')
                ->references('id')
                ->on('tenders')
                ->cascadeOnDelete();

            $table->foreign('created_by', 'tsd_created_by_fk')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->foreign('updated_by', 'tsd_updated_by_fk')
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
        Schema::dropIfExists('technical_specification_documents');
    }
};
