<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reserves one vendor row of one checklist item to a single evaluator during a
 * live session, so two members cannot evaluate the same document at once.
 *
 * The unique index is the concurrency control itself: a simultaneous second
 * INSERT fails on duplicate key, so exactly one caller wins the race. Rows are
 * DELETED on release (not soft-flagged) — MySQL has no partial unique index, so
 * a retained row would block re-locking. History lives in
 * tender_evaluation_activity_logs.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tender_evaluation_row_locks')) {
            return;
        }

        Schema::create('tender_evaluation_row_locks', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tender_id');
            $table->string('jenis_jawatankuasa', 10)->index();
            $table->uuid('checklist_item_uuid');
            $table->unsignedInteger('vendor_id');
            $table->unsignedInteger('user_id');
            $table->timestamp('locked_at');
            $table->timestamps();

            $table->unique(
                ['tender_id', 'jenis_jawatankuasa', 'checklist_item_uuid', 'vendor_id'],
                'terl_tender_jenis_item_vendor_unique'
            );

            // Poll endpoint reads by tender + jenis + item.
            $table->index(
                ['tender_id', 'jenis_jawatankuasa', 'checklist_item_uuid'],
                'terl_tender_jenis_item_index'
            );

            $table->foreign('tender_id', 'terl_tender_fk')
                ->references('id')->on('tenders')->cascadeOnDelete();

            $table->foreign('user_id', 'terl_user_fk')
                ->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_evaluation_row_locks');
    }
};
