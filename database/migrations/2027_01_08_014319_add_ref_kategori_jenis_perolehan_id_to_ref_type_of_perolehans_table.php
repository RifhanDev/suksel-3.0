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
        Schema::table('ref_type_of_perolehans', function (Blueprint $table) {
            $table->unsignedBigInteger('ref_kategori_jenis_perolehan_id')->nullable()->after('id');
            $table->foreign('ref_kategori_jenis_perolehan_id')->references('id')->on('ref_kategori_jenis_perolehans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ref_type_of_perolehans', function (Blueprint $table) {
            $table->dropForeign(['ref_kategori_jenis_perolehan_id']);
            $table->dropColumn('ref_kategori_jenis_perolehan_id');
        });
    }
};
