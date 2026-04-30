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
        Schema::create('tender_pengalaman_kerjas', function (Blueprint $table) {
            $table->id();
            $table->string('uuid', 36)->unique();
            $table->string('tender_uuid')->index();
            $table->text('tajuk');
            $table->string('pic')->nullable();
            $table->string('telefon_pic', 30)->nullable();
            $table->decimal('nilai_kerja', 15, 2)->default(0);
            $table->smallInteger('sort_order')->unsigned()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tender_pengalaman_kerjas');
    }
};
