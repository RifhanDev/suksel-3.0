<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ebidding_pengesyoran_pembekals')) {
            return;
        }

        Schema::create('ebidding_pengesyoran_pembekals', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tender_id')->unique();
            $table->text('catatan')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->foreign('tender_id')->references('id')->on('tenders')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ebidding_pengesyoran_pembekals');
    }
};
