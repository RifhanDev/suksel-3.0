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
        if (Schema::hasTable('tender_visits')) {
            return;
        }

        Schema::dropIfExists('tender_visits');

        Schema::create('tender_visits', function (Blueprint $table) {
            $table->id();
            $table->text('meetpoint');
            $table->text('address');
            $table->datetime('datetime');
            $table->boolean('required')->default(0);
            $table->unsignedInteger('tender_id');
            $table->timestamps(0);

            $table->foreign('tender_id')
                ->references('id')->on('tenders')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->index('tender_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tender_visits');
    }
};
