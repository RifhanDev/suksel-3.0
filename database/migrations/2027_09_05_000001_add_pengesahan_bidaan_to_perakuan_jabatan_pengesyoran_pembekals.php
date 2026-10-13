<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('perakuan_jabatan_pengesyoran_pembekals')) {
            return;
        }

        if (! Schema::hasColumn('perakuan_jabatan_pengesyoran_pembekals', 'pengesahan_bidaan')) {
            Schema::table('perakuan_jabatan_pengesyoran_pembekals', function (Blueprint $table) {
                $table->boolean('pengesahan_bidaan')->default(false)->after('sahkan_petender_layak');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('perakuan_jabatan_pengesyoran_pembekals')) {
            return;
        }

        if (Schema::hasColumn('perakuan_jabatan_pengesyoran_pembekals', 'pengesahan_bidaan')) {
            Schema::table('perakuan_jabatan_pengesyoran_pembekals', function (Blueprint $table) {
                $table->dropColumn('pengesahan_bidaan');
            });
        }
    }
};
