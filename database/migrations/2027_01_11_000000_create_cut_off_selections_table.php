<?php

use App\Support\SchemaCompat;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Cut-off selections (satu rekod aktif per tender).
 *
 * `tenders.id` is INT UNSIGNED on DBs restored from 2.0, BIGINT UNSIGNED on
 * fresh local migrates. Hardcoding unsignedInteger() (or unsignedBigInteger())
 * breaks the FK with MySQL error 3780 on the other environment.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Failed prior runs can leave the table without FKs and without a
        // migrations row — a bare hasTable guard would then skip forever.
        SchemaCompat::dropIfIncomplete('cut_off_selections');

        if (Schema::hasTable('cut_off_selections')) {
            return;
        }

        Schema::create('cut_off_selections', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            // Width must match tenders.id (int on restored 2.0, bigint on fresh).
            SchemaCompat::referenceColumn($table, 'tender_id', 'tenders');

            // draft = selepas Simpan, submitted = selepas Hantar (terkunci)
            $table->string('status', 20)->default('draft');

            // Snapshot PENUH jadual (semua baris: AJ + syarikat) + ringkasan pengiraan
            // pada masa Simpan/Hantar — supaya rekod sejarah tak berubah walaupun
            // data tender berubah kemudian.
            $table->json('payload');

            // Ruj (kod pembekal / 'AJ') bagi baris yang ditanda oleh pengguna.
            $table->json('selected_refs');
            $table->unsignedInteger('selected_count')->default(0);
            $table->unsignedInteger('total_count')->default(0);

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->unsignedBigInteger('submitted_by')->nullable();

            $table->timestamps();

            $table->unique('tender_id');
            $table->foreign('tender_id')->references('id')->on('tenders')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cut_off_selections');
    }
};
