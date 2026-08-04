<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCutOffSelectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cut_off_selections', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('tender_id')->unique(); // satu rekod aktif per tender

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

            $table->foreign('tender_id')->references('id')->on('tenders')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cut_off_selections');
    }
}
