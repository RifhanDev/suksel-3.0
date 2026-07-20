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
        Schema::create('technical_specification_score_rules', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('technical_specification_detail_id');
            $table->string('rule_type', 50)->index();
            $table->decimal('from_value', 15, 4)->nullable();
            $table->decimal('to_value', 15, 4)->nullable();
            $table->string('answer_value', 50)->nullable();
            $table->decimal('score', 10, 2)->default(0);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('technical_specification_detail_id', 'tssr_detail_idx');
            $table->foreign('technical_specification_detail_id', 'tssr_detail_fk')
                ->references('id')
                ->on('technical_specification_details')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technical_specification_score_rules');
    }
};
