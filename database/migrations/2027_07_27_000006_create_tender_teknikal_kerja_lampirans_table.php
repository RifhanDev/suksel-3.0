<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tender_teknikal_kerja_lampirans', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedInteger('tender_id')->index('ttkl_tender_idx');
            // Nama paparan — boleh ditukar selepas muat naik (berasingan daripada nama fail asal).
            $table->string('display_name');
            $table->string('original_name');
            $table->string('stored_name');
            $table->string('path');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('tender_id', 'ttkl_tender_fk')
                ->references('id')->on('tenders')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_teknikal_kerja_lampirans');
    }
};
