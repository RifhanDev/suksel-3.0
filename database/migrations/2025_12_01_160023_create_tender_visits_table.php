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
        Schema::dropIfExists('tender_visits');
        Schema::create('tender_visits', function (Blueprint $table) {
            $table->id();
            $table->text('meetpoint');
            $table->text('address');
            $table->datetime('datetime');
            $table->boolean('required')->default(0);
            $table->unsignedBigInteger('tender_id');
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
