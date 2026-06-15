<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lembaran_imbangans', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('tender_id')->unique()->constrained('tenders')->cascadeOnDelete();
            $table->decimal('aset_tetap', 15, 2)->default(0.00);
            $table->decimal('aset_semasa', 15, 2)->default(0.00);
            $table->decimal('liabiliti_semasa', 15, 2)->default(0.00);
            $table->decimal('liabiliti_tetap', 15, 2)->default(0.00);
            $table->decimal('wang_tunai', 15, 2)->default(0.00);
            $table->decimal('baki_kemudahan_kredit', 15, 2)->default(0.00);
            $table->string('status', 50)->default('draft');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lembaran_imbangans');
    }
};
