<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenders', function (Blueprint $table) {
            if (!Schema::hasColumn('tenders', 'ebidding_process_stage_id')) {
                $table->unsignedTinyInteger('ebidding_process_stage_id')
                    ->nullable()
                    ->after('is_ebidding')
                    ->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('tenders', function (Blueprint $table) {
            if (Schema::hasColumn('tenders', 'ebidding_process_stage_id')) {
                $table->dropIndex(['ebidding_process_stage_id']);
                $table->dropColumn('ebidding_process_stage_id');
            }
        });
    }
};
