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
        Schema::table('tenders', function (Blueprint $table) {
            $table->foreign('approver_id','tenders_approver_id_fk')->references('id')->on('users')->onDelete('set null')->nullable()->after('officer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenders', function (Blueprint $table) {
            $table->dropForeign(['approver_id']);
            $table->dropColumn('approver_id');
        });
    }
};
