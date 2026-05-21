<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('specification_pricings', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('technical_checklist_item_id')->unique()->constrained('technical_checklist_items')->cascadeOnDelete();
            $table->decimal('anggaran_jabatan', 15, 2)->default(0);
            $table->decimal('jumlah_harga', 15, 2)->default(0);
            $table->string('status', 50)->default('draft')->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('specification_pricings');
    }
};
