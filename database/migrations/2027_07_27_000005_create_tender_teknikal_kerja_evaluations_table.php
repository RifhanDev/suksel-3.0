<?php

use App\Support\SchemaCompat;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        SchemaCompat::dropIfIncomplete('tender_teknikal_kerja_evaluations');

        if (Schema::hasTable('tender_teknikal_kerja_evaluations')) {
            return;
        }

        Schema::create('tender_teknikal_kerja_evaluations', function (Blueprint $table) {
            $table->id();
            SchemaCompat::referenceColumn($table, 'tender_id', 'tenders');
            SchemaCompat::referenceColumn($table, 'vendor_id', 'vendors');
            // 'lulus' | 'tidak_lulus'
            $table->string('status', 20);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->unique(['tender_id', 'vendor_id'], 'ttke_tender_vendor_unique');

            $table->foreign('tender_id', 'ttke_tender_fk')
                ->references('id')->on('tenders')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_teknikal_kerja_evaluations');
    }
};
