<?php

use App\Support\SchemaCompat;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        SchemaCompat::dropIfIncomplete('tender_kewangan_progress');

        if (Schema::hasTable('tender_kewangan_progress')) {
            return;
        }

        Schema::create('tender_kewangan_progress', function (Blueprint $table) {
            $table->id();
            SchemaCompat::referenceColumn($table, 'tender_id', 'tenders');
            $table->tinyInteger('current_step')->unsigned()->default(1)->comment('1=Pematuhan, 2=Penyata Bank, 3=Spesifikasi, 4=Laporan');
            $table->timestamp('step1_confirmed_at')->nullable();
            SchemaCompat::referenceColumn($table, 'step1_confirmed_by', 'users', true);
            $table->timestamp('step2_confirmed_at')->nullable();
            SchemaCompat::referenceColumn($table, 'step2_confirmed_by', 'users', true);
            $table->timestamp('step3_confirmed_at')->nullable();
            SchemaCompat::referenceColumn($table, 'step3_confirmed_by', 'users', true);
            $table->timestamps();

            $table->unique('tender_id');

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
