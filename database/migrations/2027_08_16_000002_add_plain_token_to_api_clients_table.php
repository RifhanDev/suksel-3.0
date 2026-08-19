<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('api_clients')) {
            return;
        }

        if (! Schema::hasColumn('api_clients', 'plain_token')) {
            Schema::table('api_clients', function (Blueprint $table) {
                $table->text('plain_token')->nullable()->after('status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('api_clients') && Schema::hasColumn('api_clients', 'plain_token')) {
            Schema::table('api_clients', function (Blueprint $table) {
                $table->dropColumn('plain_token');
            });
        }
    }
};
