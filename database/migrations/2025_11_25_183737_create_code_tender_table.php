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
        if (Schema::hasTable('code_tender')) {
            return;
        }
        Schema::dropIfExists('code_tender');

        Schema::create('code_tender', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedBigInteger('code_id');
            $table->unsignedBigInteger('tender_id');

            $table->string('code_type', 10);

            $table->enum('inner_rule', ['and', 'or'])->nullable();
            $table->enum('join_rule', ['and', 'or'])->nullable();

            $table->integer('order')->nullable();

            $table->index('tender_id', 'fk_certification_codes_tenders_tenders1_idx');
            $table->index('code_id', 'fk_certification_codes_tenders_certification_codes1_idx');
            $table->index('code_type');

            $table->foreign('code_id', 'fk_certification_codes_tenders_certification_codes1')
                  ->references('id')->on('codes')
                  ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('tender_id', 'fk_certification_codes_tenders_tenders1')
                  ->references('id')->on('tenders')
                  ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('code_tender');
    }
};
