<?php

use Database\Seeders\StandardChecklistItemSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Mengisi `standard_checklist_items` semasa deploy.
 *
 * Senarai ini menjana pilihan pada skrin Penyediaan Spesifikasi. Empat
 * daripadanya bertaip `borang_atas_talian` — Senarai Pengalaman Kerja, Kerja
 * Dalam Tangan, Maklumat Profil Petender dan Penyata Bank — dan tanpa baris
 * berkenaan, borang itu langsung tidak muncul dalam senarai spesifikasi
 * teknikal. Itulah yang berlaku di staging.
 *
 * Seedernya sentiasa wujud dan memang selamat dijalankan berulang kali: ia
 * memadankan mengikut (title, category, type), mengemas kini yang sepadan dan
 * menyisipkan yang tiada. Yang tiada hanyalah sesuatu yang memanggilnya semasa
 * deploy. Tiada data didua di sini.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('standard_checklist_items')) {
            return;
        }

        (new StandardChecklistItemSeeder())->run();
    }

    public function down(): void
    {
        // Sengaja tidak dilaksanakan. Senarai semak tender merujuk baris ini,
        // jadi memadamnya akan merosakkan spesifikasi yang sudah dibina.
    }
};
