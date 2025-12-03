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
        Schema::create('tender_histories', function (Blueprint $table) {
            $table->id();
            $table->string('action', 45);
            $table->text('changed_data')->nullable();
            $table->integer('user_id');
            $table->integer('tender_id');
            $table->timestamps(0);
            
            $table->index('action');
            $table->index('user_id');
            $table->index('tender_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tender_histories');
    }
};
