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
        Schema::dropIfExists('tender_vendors');

        Schema::create('tender_vendors', function (Blueprint $table) {
            $table->increments('id');

            $table->decimal('amount', 30, 2)->nullable();
            $table->string('ref_number', 255)->nullable();
            $table->decimal('price', 30, 2)->default(0.00);
            $table->string('label', 255)->nullable();
            $table->boolean('exception')->default(0);
            $table->boolean('participate')->nullable()->default(0);
            $table->boolean('briefing')->default(0);
            $table->boolean('winner')->nullable()->default(0);
            $table->string('project_timeline', 255)->nullable();
            $table->boolean('submitted')->nullable()->default(0);

            $table->unsignedInteger('transaction_id');
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('tender_id');

            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->index('vendor_id', 'fk_company_tender_companies1_idx');
            $table->index('tender_id', 'fk_company_tender_tenders1_idx');
            $table->index(['id', 'transaction_id', 'tender_id'], 'vw_tndr_vdr_idx02');

            $table->foreign('vendor_id', 'fk_company_tender_companies1')
                ->references('id')->on('vendors')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('tender_id', 'fk_company_tender_tenders1')
                ->references('id')->on('tenders')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tender_vendors');
    }
};
