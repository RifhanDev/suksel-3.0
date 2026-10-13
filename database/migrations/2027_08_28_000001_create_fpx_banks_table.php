<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Mencipta `fpx_banks` pada pangkalan data yang belum memilikinya.
 *
 * Jadual ini diwarisi daripada 2.0 dan tidak pernah ditakrifkan dalam repo ini,
 * jadi ia hadir pada pangkalan data yang dipulihkan daripada dump produksi
 * (staging dan produksi) tetapi TIADA pada pemasangan baharu atau pangkalan data
 * pembangunan. Di situ senarai bank FPX tidak dapat dibaca langsung, dan
 * migration penyemai yang berjalan selepas ini gagal dengan ralat 1146.
 *
 * Skema diambil daripada App\Models\FpxBank::$fillable dan cara kod sedia ada
 * menanyakannya (skop active pada `status`, skop type pada `type`, carian ikut
 * `code`). Pangkalan data yang sudah memiliki jadual ini tidak disentuh — versi
 * warisannya kekal sebagai rujukan sebenar.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('fpx_banks')) {
            return;
        }

        Schema::create('fpx_banks', function (Blueprint $table) {
            $table->id();
            // Kod FPX rasmi PayNet. Unik: FpxBankListSeeder memadankan baris
            // mengikut kod dan menganggapnya pengecam tunggal.
            $table->string('code', 50)->unique();
            $table->string('name')->nullable();
            $table->string('display_name')->nullable();
            $table->string('type', 50)->nullable()->index();
            $table->string('status', 20)->default('active')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fpx_banks');
    }
};
