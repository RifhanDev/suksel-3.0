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
        // Restored/legacy databases may already have this table with real data.
        // Bail out BEFORE the drop below — do not touch it.
        if (Schema::hasTable('vendor_histories')) {
            return;
        }

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
