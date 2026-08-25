<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Append-only audit trail of a live evaluation session, from the member accepting
 * the declaration through to the Pengerusi submitting.
 *
 * Kept separate from tender_histories, which is a closed action enum feeding the
 * staff/user activity reports. metadata absorbs per-flow detail (chosen status,
 * step number, scores) so a new committee type never needs a schema change.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tender_evaluation_activity_logs')) {
            return;
        }

        Schema::create('tender_evaluation_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tender_id');
            $table->string('jenis_jawatankuasa', 10);
            $table->unsignedInteger('user_id')->nullable();
            // Snapshot — committee membership can change after the fact.
            $table->string('peranan', 5)->nullable();
            $table->string('action', 50);
            // Readable sentence written at log time, so the entry still reads correctly
            // if a vendor or user is renamed later.
            $table->string('description', 500)->nullable();
            $table->uuid('checklist_item_uuid')->nullable();
            $table->unsignedInteger('vendor_id')->nullable();
            $table->json('metadata')->nullable();
            $table->string('ip_address', 45)->nullable();
            // Immutable: no updated_at.
            $table->timestamp('created_at')->nullable();

            $table->index(['tender_id', 'jenis_jawatankuasa'], 'teal_tender_jenis_index');
            $table->index('user_id', 'teal_user_index');
            $table->index('action', 'teal_action_index');
            $table->index('created_at', 'teal_created_index');

            $table->foreign('tender_id', 'teal_tender_fk')
                ->references('id')->on('tenders')->cascadeOnDelete();

            $table->foreign('user_id', 'teal_user_fk')
                ->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_evaluation_activity_logs');
    }
};
