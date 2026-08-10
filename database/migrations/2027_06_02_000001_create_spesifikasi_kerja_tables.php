<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Header ────────────────────────────────────────────────────────────
        Schema::create('spesifikasi_kerja_headers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('tender_id')->unique(); // one per tender
            $table->string('status', 50)->default('draft');   // draft | submitted
            $table->timestamp('submitted_at')->nullable();
            $table->unsignedBigInteger('submitted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->index('tender_id');
        });

        // ── Items (parent Item + child Spesifikasi via parent_id) ──────────────
        Schema::create('spesifikasi_kerja_items', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('spesifikasi_kerja_header_id')
                  ->constrained('spesifikasi_kerja_headers')
                  ->cascadeOnDelete();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('nama_item')->nullable();          // parent Item title
            $table->text('spesifikasi')->nullable();          // child Spesifikasi text
            $table->string('unit', 20)->nullable();           // HB | L/S | Unit
            $table->decimal('kuantiti', 12, 2)->nullable();   // kekerapan
            $table->string('ya_tidak', 10)->nullable();       // pematuhan: ya | tidak
            $table->text('catatan')->nullable();
            $table->decimal('kadar', 15, 2)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('parent_id')
                  ->references('id')
                  ->on('spesifikasi_kerja_items')
                  ->cascadeOnDelete();

            $table->index(['spesifikasi_kerja_header_id', 'sort_order'], 'sk_items_header_sort_idx');
            $table->index('parent_id');
        });

        // ── Files ─────────────────────────────────────────────────────────────
        Schema::create('spesifikasi_kerja_files', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('spesifikasi_kerja_header_id')
                  ->constrained('spesifikasi_kerja_headers')
                  ->cascadeOnDelete();
            $table->foreignId('spesifikasi_kerja_item_id')
                  ->nullable()
                  ->constrained('spesifikasi_kerja_items')
                  ->nullOnDelete();
            $table->string('file_type', 50)->default('support'); // support | bq
            $table->string('original_name', 500);
            $table->string('stored_name', 500);
            $table->string('path', 500);
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->timestamps();

            $table->index('spesifikasi_kerja_header_id');
            $table->index('spesifikasi_kerja_item_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spesifikasi_kerja_files');
        Schema::dropIfExists('spesifikasi_kerja_items');
        Schema::dropIfExists('spesifikasi_kerja_headers');
    }
};
