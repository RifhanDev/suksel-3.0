<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tender_kewangan_kerja_evaluations')) {
            Schema::dropIfExists('tender_kewangan_kerja_evaluations');
        }

        Schema::create('tender_kewangan_kerja_evaluations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tender_id')->index();
            $table->unsignedInteger('vendor_id')->index();
            $table->string('borang_code', 50)->index();
            $table->tinyInteger('status_pematuhan')->nullable()->comment('1=Lulus, 0=Gagal, null=Belum Dinilai');
            $table->json('payload')->nullable()->comment('Custom figures and calculated financial attributes');
            $table->text('catatan')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();

            $table->unique(
                ['tender_id', 'vendor_id', 'borang_code'],
                'tkke_tender_vendor_borang_unique'
            );

            $table->foreign('tender_id', 'tkke_tender_fk')
                ->references('id')
                ->on('tenders')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_kewangan_kerja_evaluations');
    }
};
