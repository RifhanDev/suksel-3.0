<?php

use App\Support\SchemaCompat;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembelian_terus_items', function (Blueprint $table) {
            $table->id();
            SchemaCompat::referenceColumn($table, 'tender_id', 'tenders');
            $table->string('nama_item');
            $table->decimal('kuantiti', 15, 2)->default(0);
            $table->boolean('sst')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('tender_id')->references('id')->on('tenders')->onDelete('cascade');
        });

        Schema::create('pembelian_terus_offers', function (Blueprint $table) {
            $table->id();
            SchemaCompat::referenceColumn($table, 'tender_id', 'tenders');
            SchemaCompat::referenceColumn($table, 'vendor_id', 'vendors');
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->decimal('total_harga_sst', 15, 2)->default(0);
            $table->string('quotation_path')->nullable();
            $table->string('quotation_original_name')->nullable();
            $table->boolean('submitted')->default(false);
            $table->boolean('shortlisted')->default(false);
            $table->boolean('selected')->default(false);
            $table->enum('decision', ['pending', 'accepted', 'rejected'])->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('decided_at')->nullable();
            $table->timestamps();

            $table->unique(['tender_id', 'vendor_id']);
            $table->foreign('tender_id')->references('id')->on('tenders')->onDelete('cascade');
        });

        Schema::create('pembelian_terus_offer_items', function (Blueprint $table) {
            $table->id();
            SchemaCompat::referenceColumn($table, 'offer_id', 'pembelian_terus_offers');
            SchemaCompat::referenceColumn($table, 'item_id', 'pembelian_terus_items');
            $table->string('brand')->nullable();
            $table->decimal('harga_seunit', 15, 2)->default(0);
            $table->decimal('harga_keseluruhan', 15, 2)->default(0);
            $table->decimal('harga_sst', 15, 2)->default(0);
            $table->string('dokumen_sokongan_path')->nullable();
            $table->string('dokumen_sokongan_name')->nullable();
            $table->timestamps();

            $table->foreign('offer_id')->references('id')->on('pembelian_terus_offers')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('pembelian_terus_items')->onDelete('cascade');
        });

        Schema::create('pembelian_terus_documents', function (Blueprint $table) {
            $table->id();
            SchemaCompat::referenceColumn($table, 'tender_id', 'tenders');
            $table->enum('doc_type', ['jpict', 'minit_bebas', 'surat_setuju_terima', 'lain']);
            $table->string('file_path');
            $table->string('original_name')->nullable();
            SchemaCompat::referenceColumn($table, 'uploaded_by', 'users', true);
            $table->timestamps();

            $table->foreign('tender_id')->references('id')->on('tenders')->onDelete('cascade');
        });

        // Seed Kaedah Perolehan if missing
        $exists = DB::table('ref_kaedah_perolehans')
            ->where('name', 'like', '%Pembelian Terus%')
            ->exists();

        if (! $exists) {
            DB::table('ref_kaedah_perolehans')->insert([
                'name' => 'Pembelian Terus',
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pembelian_terus_documents');
        Schema::dropIfExists('pembelian_terus_offer_items');
        Schema::dropIfExists('pembelian_terus_offers');
        Schema::dropIfExists('pembelian_terus_items');
    }
};
