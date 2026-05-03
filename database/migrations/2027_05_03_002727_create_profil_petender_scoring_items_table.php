<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profil_petender_scoring_items', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('profil_petender_id')->index()->constrained('profil_petenders')->cascadeOnDelete();
            $table->string('jenis_skor', 50)->index()->comment('modal_berbayar or modal_dibenarkan');
            $table->decimal('dari', 15, 2)->default(0);
            $table->decimal('hingga', 15, 2)->nullable();
            $table->string('skema', 100)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profil_petender_scoring_items');
    }
};
