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
                if (Schema::hasTable('users')) {
Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'ic_number')) {
            $table->string('ic_number', 20)->nullable()->after('email');
            }
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('ic_number');
        });
    }
};
