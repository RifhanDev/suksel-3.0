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
        Schema::create('uploads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('uploadable_type');
            $table->unsignedBigInteger('uploadable_id');
            $table->string('token')->nullable();
            $table->string('type');
            $table->bigInteger('size');
            $table->string('url');
            $table->string('path');
            $table->boolean('public')->default(false);
            $table->string('label')->nullable();
            $table->timestamps();

            $table->index(['uploadable_type', 'uploadable_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uploads');
    }
};
