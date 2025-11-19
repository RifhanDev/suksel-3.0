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
        if (Schema::hasTable('code_requests')) {
            Schema::table('code_requests', function (Blueprint $table) {
                $table->string('rejection_template_id')->default(null)->nullable()->after('rejection_reason');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('code_requests', function (Blueprint $table) {
            //
        });
    }
};
