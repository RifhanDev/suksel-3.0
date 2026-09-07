<?php

use App\Support\SchemaCompat;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach ([
            'tender_pengalaman_kerjas',
            'tender_pengalaman_kerja_dokumens',
            'tender_kerja_dalam_tangans',
            'tender_kerja_dalam_tangan_dokumens',
        ] as $tableName) {
            if (! Schema::hasTable($tableName)) {
                continue;
            }

            if (Schema::hasColumn($tableName, 'vendor_id')) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) {
                SchemaCompat::referenceColumn($table, 'vendor_id', 'vendors', true);
            });
        }
    }

    public function down(): void
    {
        foreach ([
            'tender_pengalaman_kerjas',
            'tender_pengalaman_kerja_dokumens',
            'tender_kerja_dalam_tangans',
            'tender_kerja_dalam_tangan_dokumens',
        ] as $tableName) {
            if (! Schema::hasTable($tableName) || ! Schema::hasColumn($tableName, 'vendor_id')) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn('vendor_id');
            });
        }
    }
};
