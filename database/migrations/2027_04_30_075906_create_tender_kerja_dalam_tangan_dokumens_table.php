<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tender_kerja_dalam_tangan_dokumens', function (Blueprint $table) {
            $table->id();
            $table->string('uuid', 36)->unique();
            $table->string('tender_uuid')->index();
            $table->string('original_name');
            $table->string('stored_name');
            $table->string('path');
            $table->string('mime_type', 100)->nullable();
            $table->bigInteger('size')->unsigned()->nullable();
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_kerja_dalam_tangan_dokumens');
    }
};
