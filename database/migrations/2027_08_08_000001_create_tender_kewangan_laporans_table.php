<?php

use App\Support\SchemaCompat;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        SchemaCompat::dropIfIncomplete('tender_kewangan_laporans');

        if (Schema::hasTable('tender_kewangan_laporans')) {
            return;
        }

        Schema::create('tender_kewangan_laporans', function (Blueprint $table) {
            $table->id();
            SchemaCompat::referenceColumn($table, 'tender_id', 'tenders');
            $table->text('catatan_peringkat1')->nullable();
            $table->text('catatan_peringkat2')->nullable();
            $table->text('catatan_peringkat3')->nullable();
            $table->json('pengesyoran_justifikasi')->nullable();
            $table->string('status', 50)->default('draft');
            $table->timestamp('submitted_at')->nullable();
            SchemaCompat::referenceColumn($table, 'submitted_by', 'users', true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->unique('tender_id');

            $table->foreign('tender_id', 'tkl_tender_fk')
                ->references('id')->on('tenders')->cascadeOnDelete();

            $table->foreign('submitted_by', 'tkl_submitted_by_fk')
                ->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_kewangan_laporans');
    }
};
