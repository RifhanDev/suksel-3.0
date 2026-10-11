<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tender_iklan_dokumens')) {
            return;
        }

        Schema::create('tender_iklan_dokumens', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tender_id');
            $table->string('name');
            $table->string('original_name')->nullable();
            $table->string('path');
            $table->string('mime', 127)->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('tender_id')->references('id')->on('tenders')->cascadeOnDelete();
            $table->index(['tender_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_iklan_dokumens');
    }
};
