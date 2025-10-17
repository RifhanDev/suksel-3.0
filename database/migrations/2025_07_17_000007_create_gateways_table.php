<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('gateways', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('merchant_code')->nullable();
            $table->text('private_key')->nullable();
            $table->string('transaction_prefix')->nullable();
            $table->string('endpoint_url')->nullable();
            $table->string('daemon_url')->nullable();
            $table->string('version')->nullable();
            $table->boolean('active')->default(false);
            $table->boolean('default')->default(false);
            $table->unsignedBigInteger('organization_unit_id');
            $table->timestamps();

            $table->foreign('organization_unit_id')
                  ->references('id')->on('organization_units')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gateways');
    }
};
