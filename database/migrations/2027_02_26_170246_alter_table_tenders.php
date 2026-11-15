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
                if (Schema::hasTable('tenders')) {
Schema::table('tenders', function (Blueprint $table) {
            if (! Schema::hasColumn('tenders', 'uuid')) {
            $table->uuid('uuid')->nullable()->after('id');
            }
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenders', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
