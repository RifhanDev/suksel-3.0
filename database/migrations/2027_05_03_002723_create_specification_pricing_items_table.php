<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('specification_pricing_items', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('specification_pricing_id')->index()->constrained('specification_pricings')->cascadeOnDelete();
            $table->foreignId('spec_item_id')->nullable()->index()->constrained('technical_specification_items')->nullOnDelete();
            $table->text('title');
            $table->decimal('kuantiti', 12, 3)->default(0);
            $table->string('uom', 50)->nullable();
            $table->decimal('harga', 15, 2)->default(0);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('specification_pricing_items');
    }
};
