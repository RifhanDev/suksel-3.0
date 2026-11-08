<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('penyata_bank_files')) {
            return;
        }

        Schema::table('penyata_bank_files', function (Blueprint $table) {
            if (! Schema::hasColumn('penyata_bank_files', 'account_uuid')) {
                $table->uuid('account_uuid')->nullable()->after('vendor_id')->index();
            }
            if (! Schema::hasColumn('penyata_bank_files', 'account_index')) {
                $table->unsignedInteger('account_index')->nullable()->after('account_uuid');
            }
        });

        // Old uploads were shown under Akaun 1 by the legacy resolver.
        DB::table('penyata_bank_files')
            ->whereNull('account_index')
            ->update(['account_index' => 0]);
    }

    public function down(): void
    {
        if (! Schema::hasTable('penyata_bank_files')) {
            return;
        }

        Schema::table('penyata_bank_files', function (Blueprint $table) {
            if (Schema::hasColumn('penyata_bank_files', 'account_uuid')) {
                $table->dropIndex(['account_uuid']);
                $table->dropColumn('account_uuid');
            }
            if (Schema::hasColumn('penyata_bank_files', 'account_index')) {
                $table->dropColumn('account_index');
            }
        });
    }
};
