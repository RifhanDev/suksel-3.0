<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tender_vendor_online_form_statuses', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedInteger('tender_id')->index();
            $table->unsignedBigInteger('vendor_id')->index();
            $table->string('form_key', 80)->index();
            $table->string('status', 30)->default('draft')->index();
            $table->json('summary')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->unique(['tender_id', 'vendor_id', 'form_key'], 'tvofs_tender_vendor_form_unique');

            $table->foreign('tender_id', 'tvofs_tender_fk')
                ->references('id')
                ->on('tenders')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_vendor_online_form_statuses');
    }
};
