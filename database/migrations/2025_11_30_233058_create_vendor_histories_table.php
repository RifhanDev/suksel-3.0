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
        Schema::dropIfExists('vendor_histories');

        Schema::create('vendor_histories', function (Blueprint $table) {
            $table->id();
            $table->string('action');
            $table->text('remarks')->nullable();
            $table->integer('vendor_id');
            $table->integer('user_id');
            $table->string('rejection_reason');
            $table->string('rejection_template_id')->nullable();

            $table->timestamp('created_at')->useCurrent();

            $table->index('action');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_histories');
    }
};
