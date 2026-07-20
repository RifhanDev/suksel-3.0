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
        Schema::create('ref_organization_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->boolean('active')->default(true);
            $table->integer('is_ssm')->default(1)->comment('1: SSM, 0: No SSM');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_organization_types');
    }
};
