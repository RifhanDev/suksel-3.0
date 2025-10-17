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
        Schema::create('exception_tenders', function (Blueprint $table) {
            $table->id();
            $table->integer('vendor_id');
            $table->integer('tender_id');
            $table->integer('user_id');
            $table->integer('status')->default(0);
            $table->string('rejection_reason')->nullable();
            $table->string('rejection_template_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exception_tenders');
    }
};
