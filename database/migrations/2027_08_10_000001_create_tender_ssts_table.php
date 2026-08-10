<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tender_ssts', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('tender_id');
            $table->unsignedBigInteger('vendor_id');

            // System-generated running number, shown as No. Dokumen.
            $table->string('document_no', 60)->unique();
            $table->string('file_reference_no', 120)->nullable();
            $table->text('title')->nullable();

            // Tax is stored as a rate; total = offer price + tax.
            $table->decimal('offer_price', 15, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);

            $table->boolean('insurance')->default(false);
            $table->boolean('performance_bond')->default(false);
            $table->boolean('online_verification')->default(false);
            $table->boolean('protege_rtw')->default(false);

            $table->date('effective_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('agreement_required')->default(false);

            // Null when performance_bond is false.
            $table->decimal('bond_percentage', 5, 2)->nullable();
            $table->decimal('bond_value', 15, 2)->nullable();

            $table->string('status', 20)->default('draft');
            $table->timestamp('submitted_at')->nullable();

            // Date the letter was signed by the government, set when the letter is generated.
            $table->date('signed_at')->nullable();
            $table->unsignedBigInteger('submitted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->unique(['tender_id', 'vendor_id'], 'tender_ssts_tender_vendor_unique');
            $table->foreign('tender_id', 'tender_ssts_tender_fk')
                ->references('id')->on('tenders')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_ssts');
    }
};
