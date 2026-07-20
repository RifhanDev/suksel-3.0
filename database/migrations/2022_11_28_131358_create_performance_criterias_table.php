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
        Schema::create('performance_criterias', function (Blueprint $table) {
            $table -> id();
            $table -> double('scale_1') -> nullable();
            $table -> double('scale_2') -> nullable();
            $table -> double('scale_3') -> nullable();
            $table -> double('scale_4') -> nullable();
            $table -> double('scale_5') -> nullable();
            $table -> double('scale_6') -> nullable();
            $table -> string('review_1') -> nullable();
            $table -> string('review_2') -> nullable();
            $table -> string('review_3') -> nullable();
            $table -> string('review_4') -> nullable();
            $table -> string('review_5') -> nullable();
            $table -> string('review_6') -> nullable();
            $table -> timestamps();

            // FK
            $table -> foreignId('petender_performance_id') -> constrained('petender_performances') -> cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('performance_criterias');
    }
};
