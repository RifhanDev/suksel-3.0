<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Header ────────────────────────────────────────────────────────────
        Schema::create('kewangan_kerja_headers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('tender_id')->unique(); // one per tender
            $table->decimal('max_score', 8, 2)->default(0);
            $table->decimal('passing_score', 8, 2)->default(0);
            $table->decimal('passing_percentage', 5, 2)->default(0);
            $table->decimal('harga_indikatif', 15, 2)->nullable();
            $table->string('status', 50)->default('draft'); // draft | submitted
            $table->timestamp('submitted_at')->nullable();
            $table->unsignedBigInteger('submitted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->index('tender_id');
        });

        // ── Items ─────────────────────────────────────────────────────────────
        Schema::create('kewangan_kerja_items', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('kewangan_kerja_header_id')
                  ->constrained('kewangan_kerja_headers')
                  ->cascadeOnDelete();
            $table->unsignedBigInteger('spesifikasi_item_id')->nullable();
            $table->unsignedBigInteger('standard_item_id')->nullable();
            // source_type: specification | borang_atas_talian | standard | manual
            $table->string('source_type', 50)->default('manual');
            $table->text('title');
            // mechanism: petender_muat_naik | ptj_muat_naik | borang_atas_talian
            $table->string('mechanism', 50)->nullable();
            // vendor_action: muat_turun | muat_turun_naik | kunci_masuk
            $table->string('vendor_action', 50)->nullable();
            $table->decimal('score', 8, 2)->default(0);
            $table->string('status', 50)->default('draft');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('kewangan_kerja_header_id');
            $table->index(['kewangan_kerja_header_id', 'sort_order'], 'kk_items_header_sort_idx');
            $table->index('spesifikasi_item_id');
            $table->index('standard_item_id');
        });

        // ── Files ─────────────────────────────────────────────────────────────
        Schema::create('kewangan_kerja_files', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('kewangan_kerja_header_id')
                  ->constrained('kewangan_kerja_headers')
                  ->cascadeOnDelete();
            $table->foreignId('kewangan_kerja_item_id')
                  ->nullable()
                  ->constrained('kewangan_kerja_items')
                  ->nullOnDelete();
            $table->string('file_type', 50)->default('support');
            $table->string('original_name', 500);
            $table->string('stored_name', 500);
            $table->string('path', 500);
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->timestamps();

            $table->index('kewangan_kerja_header_id');
            $table->index('kewangan_kerja_item_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kewangan_kerja_files');
        Schema::dropIfExists('kewangan_kerja_items');
        Schema::dropIfExists('kewangan_kerja_headers');
    }
};
