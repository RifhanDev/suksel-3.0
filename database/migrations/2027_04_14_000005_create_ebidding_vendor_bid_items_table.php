<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ebidding_vendor_bid_items')) {
            return;
        }

        Schema::create('ebidding_vendor_bid_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tender_id')->index();
            $table->unsignedInteger('vendor_id')->index();
            $table->unsignedBigInteger('pemilihan_item_id')->index();
            $table->decimal('bid_price', 15, 2)->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->unique(['tender_id', 'vendor_id', 'pemilihan_item_id'], 'eb_vbid_unique');
            $table->foreign('tender_id')->references('id')->on('tenders')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->foreign('pemilihan_item_id', 'eb_vbid_item_fk')
                ->references('id')
                ->on('jawatankuasa_perolehan_pemilihan_items')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ebidding_vendor_bid_items');
    }
};
