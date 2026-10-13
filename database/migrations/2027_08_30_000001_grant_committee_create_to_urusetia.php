<?php

use Database\Seeders\StosRolePermissionSeeder;
use Illuminate\Database\Migrations\Migration;

/**
 * Menjalankan semula matriks peranan/kebenaran supaya Agency Urusetia menerima
 * 'committee:create'.
 *
 * 2027_08_27_000001 sudah menjalankan seeder yang sama dan direkodkan sebagai
 * selesai, jadi menambah kebenaran ke dalam seeder sahaja tidak akan sampai ke
 * pelayan yang sudah menjalankannya. Migration ini yang menyampaikannya.
 *
 * Tiada data didua di sini — seeder itu memeriksa kewujudan sebelum menyisip,
 * jadi ia selamat dijalankan berulang kali.
 */
return new class extends Migration
{
    public function up(): void
    {
        (new StosRolePermissionSeeder())->run();
    }

    public function down(): void
    {
        // Sengaja tidak dilaksanakan: rollback() seeder membuang keseluruhan
        // matriks, bukan hanya kebenaran yang ditambah di sini.
    }
};
