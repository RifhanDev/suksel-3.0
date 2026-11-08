<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('technical_checklist_items') && ! Schema::hasColumn('technical_checklist_items', 'penghantaran_fizikal')) {
            Schema::table('technical_checklist_items', function (Blueprint $table) {
                $table->boolean('penghantaran_fizikal')->default(false);
            });
        }

        if (Schema::hasTable('financial_checklist_items') && ! Schema::hasColumn('financial_checklist_items', 'penghantaran_fizikal')) {
            Schema::table('financial_checklist_items', function (Blueprint $table) {
                $table->boolean('penghantaran_fizikal')->default(false);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('technical_checklist_items', 'penghantaran_fizikal')) {
            Schema::table('technical_checklist_items', function (Blueprint $table) {
                $table->dropColumn('penghantaran_fizikal');
            });
        }

        if (Schema::hasColumn('financial_checklist_items', 'penghantaran_fizikal')) {
            Schema::table('financial_checklist_items', function (Blueprint $table) {
                $table->dropColumn('penghantaran_fizikal');
            });
        }
    }
};
