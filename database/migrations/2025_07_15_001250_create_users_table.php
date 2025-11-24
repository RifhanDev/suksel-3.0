<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('username')->nullable();
            $table->string('email')->unique();
            $table->string('tel')->nullable();
            $table->string('department')->nullable();
            $table->string('password');
            $table->string('confirmation_code')->nullable();
            $table->boolean('confirmed')->default(false);

            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->unsignedBigInteger('organization_unit_id')->nullable();
            $table->string('name');
            $table->boolean('approved')->default(false);
            $table->string('role_applied')->nullable();
            $table->unsignedBigInteger('approver_id')->nullable();
            $table->text('remark')->nullable();
            $table->dateTime('last_login')->nullable();

            $table->rememberToken();
            $table->timestamps();

            // Optional foreign keys if the tables exist
            // $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('set null');
            // $table->foreign('organization_unit_id')->references('id')->on('organization_units')->onDelete('set null');
            // $table->foreign('approver_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
// class CreateUsersTable extends Migration
// {
//     /**
//      * Run the migrations.
//      *
//      * @return void
//      */
//     public function up()
//     {
//         Schema::create('users', function (Blueprint $table) {
//             $table->bigIncrements('id');
//             $table->string('name');
//             $table->string('email')->unique();
//             $table->timestamp('email_verified_at')->nullable();
//             $table->string('password');
//             $table->rememberToken();
//             $table->timestamps();
//         });
//     }

//     /**
//      * Reverse the migrations.
//      *
//      * @return void
//      */
//     public function down()
//     {
//         Schema::dropIfExists('users');
//     }
// }
