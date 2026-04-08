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
        if (Schema::hasTable('perakuan_jabatan_pengesyoran_pembekals')) {
            return;
        }

        Schema::create('perakuan_jabatan_pengesyoran_pembekals', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tender_id')->unique();
            $table->text('catatan')->nullable();
            $table->boolean('sahkan_petender_layak')->default(false);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->foreign('tender_id')->references('id')->on('tenders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perakuan_jabatan_pengesyoran_pembekals');
    }
};
