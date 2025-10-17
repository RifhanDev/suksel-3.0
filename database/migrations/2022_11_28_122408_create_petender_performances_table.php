<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('petender_performances', function (Blueprint $table) {
            $table -> id();
            $table -> uuid('uuid');
            $table -> string('type') -> nullable();
            $table -> string('quantity') -> nullable();
            $table -> double('cost') -> nullable();
            $table -> date('acquisition_date') -> nullable();
            $table -> string('opinion') -> nullable();
            $table -> string('overall_review') -> nullable();
            $table -> double('total_score') -> nullable();
            $table -> timestamps();

            // FK
            $table -> integer('tender_id');
            $table -> integer('vendor_id');
            $table -> integer('appraiser_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('petender_performances');
    }
};
