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
        if (Schema::hasTable('jawatankuasa_perolehan_meetings')) {
            return;
        }

        Schema::create('jawatankuasa_perolehan_meetings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tender_id')->index();
            $table->string('bil_mesyuarat', 100);
            $table->date('tarikh_mesyuarat');
            $table->string('tajuk_agenda');
            $table->string('tempat');
            $table->string('no_kod_kertas', 100);
            $table->enum('status', ['Belum Selesai', 'Selesai'])->default('Belum Selesai');
            $table->string('catatan')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            // $table->foreign('tender_id')->references('id')->on('tenders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawatankuasa_perolehan_meetings');
    }
};
