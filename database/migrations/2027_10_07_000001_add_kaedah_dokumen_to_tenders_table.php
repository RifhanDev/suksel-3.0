<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tenders') || Schema::hasColumn('tenders', 'kaedah_dokumen')) {
            return;
        }

        Schema::table('tenders', function (Blueprint $table) {
            $table->string('kaedah_dokumen', 32)->nullable()->after('only_advertise');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('tenders') || ! Schema::hasColumn('tenders', 'kaedah_dokumen')) {
            return;
        }

        Schema::table('tenders', function (Blueprint $table) {
            $table->dropColumn('kaedah_dokumen');
        });
    }
};
