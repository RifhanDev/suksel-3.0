<?php

use App\Support\SchemaCompat;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        SchemaCompat::dropIfIncomplete('tender_kewangan_evaluations');

        if (Schema::hasTable('tender_kewangan_evaluations')) {
            return;
        }

        Schema::create('tender_kewangan_evaluations', function (Blueprint $table) {
            $table->id();
            SchemaCompat::referenceColumn($table, 'tender_id', 'tenders');
            SchemaCompat::referenceColumn($table, 'vendor_id', 'vendors');
            $table->uuid('checklist_item_uuid')->index();
            // 1 = Mematuhi, 0 = Tidak Mematuhi
            $table->tinyInteger('status_pematuhan')->default(1);
            // Required when status_pematuhan = 0
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->unique(
                ['tender_id', 'vendor_id', 'checklist_item_uuid'],
                'tke_tender_vendor_item_unique'
            );

            $table->foreign('tender_id', 'tke_tender_fk')
                ->references('id')
                ->on('tenders')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_kewangan_evaluations');
    }
};
