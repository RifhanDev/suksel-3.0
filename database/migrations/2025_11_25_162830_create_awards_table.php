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
        Schema::create('awards', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->string('by', 255)->nullable();
            $table->unsignedBigInteger('vendor_id');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');

            $table->foreign('vendor_id')
                  ->references('id')
                  ->on('vendors')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->index('vendor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('awards');
    }
};
