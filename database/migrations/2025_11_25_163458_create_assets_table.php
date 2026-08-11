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
        if (Schema::hasTable('assets')) {
            return;
        }
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('name', 128);
            $table->decimal('value', 20, 2);
            $table->unsignedInteger('vendor_id');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->index('vendor_id', 'fk_assets_companies1_idx');
            $table->foreign('vendor_id')
                  ->references('id')
                  ->on('vendors')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
