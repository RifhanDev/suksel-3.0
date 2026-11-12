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
                if (Schema::hasTable('complaints')) {
Schema::table('complaints', function (Blueprint $table) {
            if (! Schema::hasColumn('complaints', 'tender_id')) {
            $table->unsignedBigInteger('tender_id')->nullable()->after('module');
            }
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropColumn('tender_id');
        });
    }
};
