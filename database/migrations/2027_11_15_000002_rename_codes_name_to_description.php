<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (
            Schema::hasTable('codes')
            && Schema::hasColumn('codes', 'name')
            && ! Schema::hasColumn('codes', 'description')
        ) {
            Schema::table('codes', function (Blueprint $table) {
                $table->renameColumn('name', 'description');
            });
        }
    }

    public function down(): void
    {
        if (
            Schema::hasTable('codes')
            && Schema::hasColumn('codes', 'description')
            && ! Schema::hasColumn('codes', 'name')
        ) {
            Schema::table('codes', function (Blueprint $table) {
                $table->renameColumn('description', 'name');
            });
        }
    }
};
