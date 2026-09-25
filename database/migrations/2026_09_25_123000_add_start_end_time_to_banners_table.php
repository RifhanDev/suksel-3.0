<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('banners')) {
            return;
        }

        Schema::table('banners', function (Blueprint $table) {
            if (! Schema::hasColumn('banners', 'start_time')) {
                $table->time('start_time')->nullable()->after('start');
            }
            if (! Schema::hasColumn('banners', 'end_time')) {
                $table->time('end_time')->nullable()->after('end');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('banners')) {
            return;
        }

        Schema::table('banners', function (Blueprint $table) {
            if (Schema::hasColumn('banners', 'start_time')) {
                $table->dropColumn('start_time');
            }
            if (Schema::hasColumn('banners', 'end_time')) {
                $table->dropColumn('end_time');
            }
        });
    }
};
