<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('ref_type_of_perolehans')) {
            return;
        }

        if (! Schema::hasColumn('ref_type_of_perolehans', 'ref_kategori_jenis_perolehan_id')) {
            Schema::table('ref_type_of_perolehans', function (Blueprint $table) {
                $table->unsignedBigInteger('ref_kategori_jenis_perolehan_id')->nullable()->after('id');
            });
        }

        $fkExists = collect(DB::select(
            "SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = 'ref_type_of_perolehans'
               AND COLUMN_NAME = 'ref_kategori_jenis_perolehan_id'
               AND REFERENCED_TABLE_NAME IS NOT NULL"
        ))->isNotEmpty();

        if (! $fkExists && Schema::hasTable('ref_kategori_jenis_perolehans')) {
            Schema::table('ref_type_of_perolehans', function (Blueprint $table) {
                $table->foreign('ref_kategori_jenis_perolehan_id')
                    ->references('id')
                    ->on('ref_kategori_jenis_perolehans')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('ref_type_of_perolehans')) {
            return;
        }

        if (Schema::hasColumn('ref_type_of_perolehans', 'ref_kategori_jenis_perolehan_id')) {
            Schema::table('ref_type_of_perolehans', function (Blueprint $table) {
                $table->dropForeign(['ref_kategori_jenis_perolehan_id']);
                $table->dropColumn('ref_kategori_jenis_perolehan_id');
            });
        }
    }
};
