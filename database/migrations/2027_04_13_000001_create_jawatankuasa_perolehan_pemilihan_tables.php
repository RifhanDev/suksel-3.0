<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('jawatankuasa_perolehan_pemilihan_headers')) {
            Schema::create('jawatankuasa_perolehan_pemilihan_headers', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('tender_id')->unique();
                $table->string('keputusan_mesyuarat', 255)->nullable();
                $table->string('kaedah_memuktamadkan_pembekal', 255)->nullable();
                $table->string('pemilihan_berdasarkan', 255)->nullable();
                $table->string('loi_loa_disediakan_oleh', 255)->nullable();
                $table->string('bil_mesyuarat', 100)->nullable();
                $table->string('no_kod', 100)->nullable();
                $table->boolean('sahkan_layak_bidaan')->default(false);
                $table->timestamp('submitted_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('jawatankuasa_perolehan_pemilihan_items')) {
            Schema::create('jawatankuasa_perolehan_pemilihan_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('tender_id')->index();
                $table->unsignedInteger('sort_order')->default(1);
                $table->text('perihal_item');
                $table->string('jenis_item', 255)->nullable();
                $table->string('unit_ukuran', 255)->nullable();
                $table->string('jenis_harga', 255)->nullable();
                $table->string('dibatalkan', 10)->default('Tidak');
                $table->unsignedInteger('pembekal_dipilih')->default(0);
                $table->decimal('kuantiti', 15, 4)->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('jawatankuasa_perolehan_pemilihan_petenders')) {
            Schema::create('jawatankuasa_perolehan_pemilihan_petenders', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('pemilihan_item_id');
                $table->unsignedInteger('sort_order')->default(1);
                $table->string('bil_label', 30)->nullable();
                $table->string('status_bumiputra', 10)->default('Tidak');
                $table->decimal('harga_tawaran', 15, 2)->default(0);
                $table->decimal('jumlah_skor', 10, 2)->default(0);
                $table->unsignedInteger('kedudukan_penilaian')->nullable();
                $table->string('status_mof', 100)->nullable();
                $table->text('tindakan_disiplin')->nullable();
                $table->string('lembaga_pengarah_file_path', 500)->nullable();
                $table->string('keputusan_urusetia', 100)->nullable();
                $table->text('catatan_urusetia')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('jawatankuasa_perolehan_pemilihan_petenders');
        Schema::dropIfExists('jawatankuasa_perolehan_pemilihan_items');
        Schema::dropIfExists('jawatankuasa_perolehan_pemilihan_headers');
    }
};
