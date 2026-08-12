<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Alters an already-created spesifikasi_kerja_items table to the CR-004 shape.
 * Safe no-op when columns already exist (fresh installs use the updated create migration).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('spesifikasi_kerja_items')) {
            return;
        }

        Schema::table('spesifikasi_kerja_items', function (Blueprint $table) {
            if (! Schema::hasColumn('spesifikasi_kerja_items', 'parent_id')) {
                $table->unsignedBigInteger('parent_id')->nullable()->after('spesifikasi_kerja_header_id');
                $table->foreign('parent_id')
                      ->references('id')
                      ->on('spesifikasi_kerja_items')
                      ->cascadeOnDelete();
                $table->index('parent_id');
            }

            if (! Schema::hasColumn('spesifikasi_kerja_items', 'nama_item')) {
                $table->string('nama_item')->nullable()->after('parent_id');
            }

            if (! Schema::hasColumn('spesifikasi_kerja_items', 'unit')) {
                $table->string('unit', 20)->nullable()->after('spesifikasi');
            }

            if (! Schema::hasColumn('spesifikasi_kerja_items', 'kuantiti')) {
                $table->decimal('kuantiti', 12, 2)->nullable()->after('unit');
            }

            if (! Schema::hasColumn('spesifikasi_kerja_items', 'kadar')) {
                $table->decimal('kadar', 15, 2)->nullable()->after('catatan');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('spesifikasi_kerja_items')) {
            return;
        }

        Schema::table('spesifikasi_kerja_items', function (Blueprint $table) {
            if (Schema::hasColumn('spesifikasi_kerja_items', 'parent_id')) {
                $table->dropForeign(['parent_id']);
                $table->dropIndex(['parent_id']);
                $table->dropColumn('parent_id');
            }

            foreach (['nama_item', 'unit', 'kuantiti', 'kadar'] as $column) {
                if (Schema::hasColumn('spesifikasi_kerja_items', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
