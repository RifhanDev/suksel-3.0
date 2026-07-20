<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penyata_bank_scoring_items', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('penyata_bank_id')->index()->constrained('penyata_banks')->cascadeOnDelete();
            $table->decimal('dari', 15, 2)->default(0);
            $table->decimal('hingga', 15, 2)->nullable();
            $table->string('skema', 100)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penyata_bank_scoring_items');
    }
};
