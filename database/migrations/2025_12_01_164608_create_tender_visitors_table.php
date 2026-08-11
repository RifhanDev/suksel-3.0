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
        if (Schema::hasTable('tender_visitors')) {
            return;
        }

        Schema::dropIfExists('tender_visitors');

        Schema::create('tender_visitors', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('vendor_id');
            $table->unsignedBigInteger('visit_id');
            $table->timestamps(0);

            $table->foreign('vendor_id')
                ->references('id')->on('vendors')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('visit_id')
                ->references('id')->on('tender_visits')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->index('vendor_id');
            $table->index('visit_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tender_visitors');
    }
};
