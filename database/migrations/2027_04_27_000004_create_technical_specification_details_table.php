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
        Schema::create('technical_specification_details', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('technical_specification_item_id');
            $table->text('description')->nullable();
            $table->string('response_type', 50)->nullable();
            $table->string('score_mode', 50)->nullable();
            $table->decimal('max_score', 10, 2)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('technical_specification_item_id', 'tsdet_item_idx');
            $table->foreign('technical_specification_item_id', 'tsdet_item_fk')
                ->references('id')
                ->on('technical_specification_items')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technical_specification_details');
    }
};
