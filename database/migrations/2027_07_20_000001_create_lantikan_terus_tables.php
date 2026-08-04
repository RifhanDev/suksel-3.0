<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lantikan_terus_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tender_id')->index();
            // bq = dokumen BQ pemilik projek; jpict/minit_bebas = cut-off; surat_setuju_terima = keputusan
            $table->enum('doc_type', ['bq', 'jpict', 'minit_bebas', 'surat_setuju_terima', 'lain']);
            $table->string('file_path');
            $table->string('original_name')->nullable();
            $table->string('display_name')->nullable();
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->timestamps();

            $table->foreign('tender_id')->references('id')->on('tenders')->onDelete('cascade');
        });

        Schema::create('lantikan_terus_offers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tender_id')->index();
            $table->unsignedBigInteger('vendor_id')->index();
            $table->decimal('harga_tawaran', 15, 2)->default(0);
            $table->string('bq_path')->nullable();
            $table->string('bq_original_name')->nullable();
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

        $exists = DB::table('ref_kaedah_perolehans')
            ->where('name', 'like', '%Lantikan Terus%')
            ->exists();

        if (! $exists) {
            DB::table('ref_kaedah_perolehans')->insert([
                'name' => 'Lantikan Terus',
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('lantikan_terus_offers');
        Schema::dropIfExists('lantikan_terus_documents');
    }
};
