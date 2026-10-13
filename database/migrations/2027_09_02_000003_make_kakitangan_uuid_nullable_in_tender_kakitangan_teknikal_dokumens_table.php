<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        try {
            Schema::table('tender_kakitangan_teknikal_dokumens', function (Blueprint $table) {
                $table->string('kakitangan_uuid', 36)->nullable()->change();
            });
        } catch (\Throwable $e) {
            DB::statement("ALTER TABLE `tender_kakitangan_teknikal_dokumens` MODIFY `kakitangan_uuid` VARCHAR(36) NULL;");
        }
    }

    public function down(): void
    {
        try {
            Schema::table('tender_kakitangan_teknikal_dokumens', function (Blueprint $table) {
                $table->string('kakitangan_uuid', 36)->nullable(false)->change();
            });
        } catch (\Throwable $e) {
            DB::statement("ALTER TABLE `tender_kakitangan_teknikal_dokumens` MODIFY `kakitangan_uuid` VARCHAR(36) NOT NULL;");
        }
    }
};
