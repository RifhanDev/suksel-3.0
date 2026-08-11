<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = ['bon_sahams', 'tender_prestasi_kerjas', 'lembaran_imbangans'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropForeign(['tender_id']);
            });

            Schema::table($tableName, function (Blueprint $table) {
                $table->dropUnique(['tender_id']);
            });

            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $table->unsignedBigInteger('vendor_id')->nullable()->after('tender_id')->index();
                $table->foreign('tender_id')->references('id')->on('tenders')->cascadeOnDelete();
                $table->unique(['tender_id', 'vendor_id'], $tableName . '_tender_vendor_unique');
            });
        }

        Schema::create('tender_vendor_form_payloads', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedInteger('tender_id');
            $table->foreign('tender_id')->references('id')->on('tenders')->cascadeOnDelete();
            $table->unsignedBigInteger('vendor_id')->index();
            $table->string('form_key', 50)->index();
            $table->json('payload')->nullable();
            $table->string('status', 30)->default('draft')->index();
            $table->timestamp('submitted_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->unique(['tender_id', 'vendor_id', 'form_key'], 'tvfp_tender_vendor_form_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_vendor_form_payloads');

        foreach (['bon_sahams', 'tender_prestasi_kerjas', 'lembaran_imbangans'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $table->dropForeign(['tender_id']);
                $table->dropUnique($tableName . '_tender_vendor_unique');
                $table->dropColumn('vendor_id');
            });

            Schema::table($tableName, function (Blueprint $table) {
                $table->unique('tender_id');
                $table->foreign('tender_id')->references('id')->on('tenders')->cascadeOnDelete();
            });
        }
    }
};
