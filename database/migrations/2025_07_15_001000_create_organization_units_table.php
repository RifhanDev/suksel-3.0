<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationUnitsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('organization_units')) {
            return;
        }
        Schema::create('organization_units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_name')->nullable();
            $table->unsignedInteger('parent_id')->nullable()->references('id')->on('organization_units')->nullOnDelete();
            $table->unsignedBigInteger('lft')->nullable();
            $table->unsignedBigInteger('rgt')->nullable();
            $table->unsignedBigInteger('depth')->nullable();
            $table->unsignedBigInteger('district_id')->nullable();
            $table->string('latlng',128)->nullable();
            $table->text('address')->nullable();
            $table->string('tel')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->boolean('confirmation_agency')->default(false);
            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->unsignedInteger('type_id')->nullable();
            $table->foreign('type_id')->references('id')->on('organization_types')->nullOnDelete();
            $table->integer('sort_no')->nullable();
            $table->integer('ori_type_id')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_units');
    }
}
