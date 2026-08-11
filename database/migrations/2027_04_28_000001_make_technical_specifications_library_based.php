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
        if (Schema::hasColumn('technical_specification_documents', 'tender_id')) {
            Schema::table('technical_specification_documents', function (Blueprint $table) {
                $table->dropForeign('tsd_tender_fk');
                $table->dropColumn('tender_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('technical_specification_documents', 'tender_id')) {
            Schema::table('technical_specification_documents', function (Blueprint $table) {
                $table->unsignedInteger('tender_id')->nullable()->after('uuid');
                $table->index('tender_id', 'technical_specification_documents_tender_id_index');
                $table->foreign('tender_id', 'tsd_tender_fk')
                    ->references('id')
                    ->on('tenders')
                    ->nullOnDelete();
            });
        }
    }
};
