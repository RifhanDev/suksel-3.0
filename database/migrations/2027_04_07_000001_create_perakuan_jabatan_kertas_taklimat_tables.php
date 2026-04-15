<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('perakuan_jabatan_kertas_taklimats')) {
            Schema::create('perakuan_jabatan_kertas_taklimats', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('tender_id')->unique();
                $table->text('catatan')->nullable();
                $table->timestamp('submitted_at')->nullable();
                $table->timestamps();
                $table->foreign('tender_id')->references('id')->on('tenders')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('perakuan_jabatan_kertas_taklimat_items')) {
            Schema::create('perakuan_jabatan_kertas_taklimat_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('kertas_taklimat_id')->index();
                $table->string('slot_key', 64)->nullable()->index();
                $table->string('kandungan', 500);
                $table->unsignedSmallInteger('sort_order')->default(0);
                $table->timestamps();

                // $table->foreign('kertas_taklimat_id', 'pjk_ti_kt_fk')
                //     ->references('id')
                //     ->on('perakuan_jabatan_kertas_taklimats')
                //     ->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('perakuan_jabatan_kertas_taklimat_item_files')) {
            Schema::create('perakuan_jabatan_kertas_taklimat_item_files', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('item_id')->index();
                $table->string('file_path');
                $table->string('file_original_name', 255);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perakuan_jabatan_kertas_taklimat_item_files');
        Schema::dropIfExists('perakuan_jabatan_kertas_taklimat_items');
        Schema::dropIfExists('perakuan_jabatan_kertas_taklimats');
    }
};
