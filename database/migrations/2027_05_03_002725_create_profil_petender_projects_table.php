<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profil_petender_projects', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('profil_petender_id')->index()->constrained('profil_petenders')->cascadeOnDelete();
            $table->string('nama', 255)->nullable();
            $table->string('agensi', 255)->nullable();
            $table->decimal('nilai_projek', 15, 2)->default(0);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profil_petender_projects');
    }
};
