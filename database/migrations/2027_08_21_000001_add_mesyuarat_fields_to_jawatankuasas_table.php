<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * CR-005: perincian mesyuarat on Jawatankuasa Spesifikasi pelantikan.
 * Safe no-op when columns already exist (fresh installs use the updated create migration).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('jawatankuasas')) {
            return;
        }

        Schema::table('jawatankuasas', function (Blueprint $table) {
            if (! Schema::hasColumn('jawatankuasas', 'tarikh_mesyuarat')) {
                $table->date('tarikh_mesyuarat')->nullable()->after('catatan');
            }

            if (! Schema::hasColumn('jawatankuasas', 'masa_mesyuarat')) {
                $table->string('masa_mesyuarat', 10)->nullable()->after('tarikh_mesyuarat');
            }

            if (! Schema::hasColumn('jawatankuasas', 'lokasi_mesyuarat')) {
                $table->string('lokasi_mesyuarat')->nullable()->after('masa_mesyuarat');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('jawatankuasas')) {
            return;
        }

        Schema::table('jawatankuasas', function (Blueprint $table) {
            foreach (['tarikh_mesyuarat', 'masa_mesyuarat', 'lokasi_mesyuarat'] as $column) {
                if (Schema::hasColumn('jawatankuasas', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
