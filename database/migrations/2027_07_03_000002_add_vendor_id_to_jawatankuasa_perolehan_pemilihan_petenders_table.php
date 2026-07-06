<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('jawatankuasa_perolehan_pemilihan_petenders')) {
            return;
        }

        Schema::table('jawatankuasa_perolehan_pemilihan_petenders', function (Blueprint $table) {
            if (! Schema::hasColumn('jawatankuasa_perolehan_pemilihan_petenders', 'vendor_id')) {
                $table->unsignedBigInteger('vendor_id')->nullable()->after('pemilihan_item_id');
                $table->index('vendor_id');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('jawatankuasa_perolehan_pemilihan_petenders')) {
            return;
        }

        Schema::table('jawatankuasa_perolehan_pemilihan_petenders', function (Blueprint $table) {
            if (Schema::hasColumn('jawatankuasa_perolehan_pemilihan_petenders', 'vendor_id')) {
                $table->dropIndex(['vendor_id']);
                $table->dropColumn('vendor_id');
            }
        });
    }
};
