<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('jawatankuasa_perolehan_pemilihan_headers')) {
            return;
        }

        if (Schema::hasColumn('jawatankuasa_perolehan_pemilihan_headers', 'catatan_bidaan')) {
            return;
        }

        Schema::table('jawatankuasa_perolehan_pemilihan_headers', function (Blueprint $table) {
            $table->text('catatan_bidaan')->nullable()->after('sahkan_layak_bidaan');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('jawatankuasa_perolehan_pemilihan_headers')) {
            return;
        }

        if (! Schema::hasColumn('jawatankuasa_perolehan_pemilihan_headers', 'catatan_bidaan')) {
            return;
        }

        Schema::table('jawatankuasa_perolehan_pemilihan_headers', function (Blueprint $table) {
            $table->dropColumn('catatan_bidaan');
        });
    }
};
