<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
                if (Schema::hasTable('tender_vendors')) {
Schema::table('tender_vendors', function (Blueprint $table) {
            if (! Schema::hasColumn('tender_vendors', 'kod_pembekal')) {
            $table->string('kod_pembekal', 20)->nullable()->after('ref_number');
            }
        });
        }
    }

    public function down(): void
    {
        Schema::table('tender_vendors', function (Blueprint $table) {
            $table->dropColumn('kod_pembekal');
        });
    }
};
