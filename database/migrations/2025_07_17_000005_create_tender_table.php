<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenderTable extends Migration
{
    public function up(): void
    {
        // Restored/legacy databases (e.g. staging seeded from a production backup)
        // may already have this table with its full accumulated real-world schema.
        // Skip creation there — only build it from scratch on a genuinely fresh DB.
        if (Schema::hasTable('tenders')) {
            return;
        }

        Schema::create('tenders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ref_number')->nullable();
            $table->unsignedInteger('creator_id')->nullable();
            $table->unsignedInteger('officer_id')->nullable();
            $table->unsignedInteger('approver_id')->nullable();
            $table->unsignedInteger('organization_unit_id');
            $table->decimal('price', 15, 2)->nullable();
            $table->boolean('allow_exception')->default(false);
            $table->date('advertise_start_date')->nullable();
            $table->date('advertise_stop_date')->nullable();
            $table->date('document_start_date')->nullable();
            $table->date('document_stop_date')->nullable();
            $table->dateTime('submission_datetime')->nullable();
            $table->text('submission_location_address')->nullable();
            $table->text('tender_rules')->nullable();
            $table->boolean('publish_prices')->default(false);
            $table->boolean('publish_shortlists')->default(false);
            $table->boolean('publish_winner')->default(false);
            $table->dateTime('briefing_datetime')->nullable();
            $table->text('briefing_address')->nullable();
            $table->string('briefing_latlng')->nullable();
            $table->boolean('briefing_required')->default(false);
            $table->boolean('invitation')->default(false);
            $table->unsignedBigInteger('district_id')->nullable();
            $table->boolean('only_selangor')->default(false);
            $table->boolean('only_bumiputera')->default(false);
            $table->string('type')->nullable();
            $table->boolean('only_advertise')->default(false);
            $table->text('mof_cidb_rule')->nullable();
            $table->text('district_list_rule')->nullable();

            $table->timestamps();

            $table->foreign('organization_unit_id')->references('id')->on('organization_units')->onDelete('cascade');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('officer_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approver_id')->references('id')->on('users')->onDelete('set null');
            // $table->foreign('district_id')->references('id')->on('districts')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenders');
    }
}
