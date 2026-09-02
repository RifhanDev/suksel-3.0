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
        if (! Schema::hasTable('jawatankuasa_perolehan_meetings')) {
            return;
        }

        if (! Schema::hasColumn('jawatankuasa_perolehan_meetings', 'masa')) {
            Schema::table('jawatankuasa_perolehan_meetings', function (Blueprint $table) {
                $table->string('masa', 10)->nullable()->after('tarikh_mesyuarat');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('jawatankuasa_perolehan_meetings')) {
            return;
        }

        if (Schema::hasColumn('jawatankuasa_perolehan_meetings', 'masa')) {
            Schema::table('jawatankuasa_perolehan_meetings', function (Blueprint $table) {
                $table->dropColumn('masa');
            });
        }
    }
};
