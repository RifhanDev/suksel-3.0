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
        Schema::create('bon_sahams', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('tender_id')->unique()->constrained('tenders')->cascadeOnDelete();
            $table->decimal('jumlah_keseluruhan', 15, 2)->default(0.00);
            $table->string('status', 50)->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('bon_saham_accounts', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('bon_saham_id')->constrained('bon_sahams')->cascadeOnDelete();
            $table->string('bank_institusi', 255)->nullable();
            $table->decimal('jumlah_deposit', 15, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bon_saham_accounts');
        Schema::dropIfExists('bon_sahams');
    }
};
