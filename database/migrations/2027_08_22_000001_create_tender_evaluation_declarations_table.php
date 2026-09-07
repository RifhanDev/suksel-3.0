<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Akuan Pengakuan accepted by a committee member before a live evaluation session.
 * Generic across committee types — jenis_jawatankuasa scopes each row
 * ('open' = Pembuka, 'tech' = Penilaian Teknikal, 'fin' = Penilaian Kewangan).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tender_evaluation_declarations')) {
            return;
        }

        Schema::create('tender_evaluation_declarations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tender_id');
            $table->string('jenis_jawatankuasa', 10)->index();
            $table->unsignedInteger('user_id');
            // Snapshot of the member's role at the time of agreement (1=Pengerusi, 2=Setiausaha, 3=Ahli).
            $table->string('peranan', 5)->nullable();
            $table->timestamp('agreed_at');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->unique(
                ['tender_id', 'jenis_jawatankuasa', 'user_id'],
                'ted_tender_jenis_user_unique'
            );

            $table->foreign('tender_id', 'ted_tender_fk')
                ->references('id')->on('tenders')->cascadeOnDelete();

            $table->foreign('user_id', 'ted_user_fk')
                ->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_evaluation_declarations');
    }
};
