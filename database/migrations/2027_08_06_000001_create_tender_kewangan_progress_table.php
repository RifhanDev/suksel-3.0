<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tender_kewangan_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tender_id')->unique();
            $table->tinyInteger('current_step')->unsigned()->default(1)->comment('1=Pematuhan, 2=Penyata Bank, 3=Spesifikasi, 4=Laporan');
            $table->timestamp('step1_confirmed_at')->nullable();
            $table->unsignedInteger('step1_confirmed_by')->nullable();
            $table->timestamp('step2_confirmed_at')->nullable();
            $table->unsignedInteger('step2_confirmed_by')->nullable();
            $table->timestamp('step3_confirmed_at')->nullable();
            $table->unsignedInteger('step3_confirmed_by')->nullable();
            $table->timestamps();

            $table->foreign('tender_id', 'tkp_tender_fk')
                ->references('id')
                ->on('tenders')
                ->onDelete('cascade');

            $table->foreign('step1_confirmed_by', 'tkp_step1_user_fk')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->foreign('step2_confirmed_by', 'tkp_step2_user_fk')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->foreign('step3_confirmed_by', 'tkp_step3_user_fk')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_kewangan_progress');
    }
};
