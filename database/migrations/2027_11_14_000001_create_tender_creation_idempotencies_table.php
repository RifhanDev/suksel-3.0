<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tender_creation_idempotencies', function (Blueprint $table) {
            $table->string('idempotency_key', 36)->primary();
            $table->char('request_hash', 64);
            $table->unsignedBigInteger('tender_id')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_creation_idempotencies');
    }
};
