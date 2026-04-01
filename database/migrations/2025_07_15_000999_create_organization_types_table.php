<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationTypesTable extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('organization_types')) {
            return;
        }
        Schema::create('organization_types', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name', 45)->charset('utf8mb3')->collation('utf8mb3_general_ci');

            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();

            $table->integer('sort_no')->nullable();

            $table->integer('ori_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_types');
    }
}
