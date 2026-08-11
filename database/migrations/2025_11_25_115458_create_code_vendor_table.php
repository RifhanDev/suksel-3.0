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
        if (Schema::hasTable('code_vendor')) {
            return;
        }
        Schema::create('code_vendor', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedInteger('vendor_id');
            $table->unsignedBigInteger('code_id');;
            $table->string('code_type', 10)->nullable();

            // Define foreign key constraints
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('code_vendor')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('code_id')
                  ->references('id')
                  ->on('codes')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('vendor_id')
                  ->references('id')
                  ->on('vendors')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            // Create indexes (Optional)
            $table->index('code_type');
            $table->index('code_id');
            $table->index('vendor_id');
            $table->index('parent_id');

            // Timestamps (if required)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('code_vendor');
    }
};
