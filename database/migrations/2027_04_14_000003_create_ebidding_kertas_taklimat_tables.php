<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('ebidding_kertas_taklimats')) {
            Schema::create('ebidding_kertas_taklimats', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('tender_id')->unique();
                $table->text('catatan')->nullable();
                $table->timestamp('submitted_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('ebidding_kertas_taklimat_items')) {
            Schema::create('ebidding_kertas_taklimat_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('kertas_taklimat_id')->index();
                $table->string('slot_key', 64)->nullable()->index();
                $table->string('kandungan', 500);
                $table->unsignedSmallInteger('sort_order')->default(0);
                $table->timestamps();

                $table->foreign('kertas_taklimat_id', 'eb_kt_item_header_fk')
                    ->references('id')
                    ->on('ebidding_kertas_taklimats')
                    ->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('ebidding_kertas_taklimat_item_files')) {
            Schema::create('ebidding_kertas_taklimat_item_files', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('item_id')->index();
                $table->string('file_path');
                $table->string('file_original_name', 255);
                $table->timestamps();

                $table->foreign('item_id', 'eb_kt_file_item_fk')
                    ->references('id')
                    ->on('ebidding_kertas_taklimat_items')
                    ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ebidding_kertas_taklimat_item_files');
        Schema::dropIfExists('ebidding_kertas_taklimat_items');
        Schema::dropIfExists('ebidding_kertas_taklimats');
    }
};
