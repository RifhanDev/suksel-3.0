<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penyata_banks', function (Blueprint $table) {
            $table->json('accounts')->nullable()->after('jenis_skor_purata');
        });
    }

    public function down(): void
    {
        Schema::table('penyata_banks', function (Blueprint $table) {
            $table->dropColumn('accounts');
        });
    }
};
