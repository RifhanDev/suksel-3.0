<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('perakuan_jabatan_pengesyoran_pembekal_items')) {
            return;
        }

        Schema::create('perakuan_jabatan_pengesyoran_pembekal_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengesyoran_pembekal_id');
            $table->unsignedBigInteger('vendor_id');
            $table->string('syor_urusetia', 50)->nullable();
            $table->text('catatan_urusetia')->nullable();
            $table->timestamps();

            $table->unique(['pengesyoran_pembekal_id', 'vendor_id'], 'pj_pp_items_pengesyoran_vendor_unique');
            $table->foreign('pengesyoran_pembekal_id', 'pj_pp_items_pengesyoran_fk')
                ->references('id')
                ->on('perakuan_jabatan_pengesyoran_pembekals')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perakuan_jabatan_pengesyoran_pembekal_items');
    }
};
