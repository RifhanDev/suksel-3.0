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
            $table->string('username', 128)->unique();
            $table->string('email', 128)->unique();
            $table->string('name', 128)->nullable();
            $table->string('tel', 255)->nullable();
            $table->string('department', 255)->nullable();
            $table->string('password', 128);
            $table->string('confirmation_code', 128)->nullable();
            $table->unsignedTinyInteger('confirmed')->default(0);
            $table->string('remember_token', 128)->nullable();
            $table->string('auth_token', 128)->nullable();
            $table->unsignedBigInteger('organization_unit_id')->nullable();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->string('email_token', 128)->nullable();
            $table->timestamp('email_verify_at')->nullable();
            $table->string('unconfirmed_email_token', 128)->nullable();
            $table->string('unconfirmed_email', 128)->nullable();
            $table->unsignedTinyInteger('approved')->nullable()->default(1);
            $table->unsignedInteger('role_applied')->nullable();
            $table->unsignedBigInteger('approver_id')->nullable();
            $table->text('remark')->nullable();
            $table->dateTime('arr_sent_at')->nullable();
            $table->unsignedTinyInteger('arr')->nullable();
            $table->timestamps();
            $table->timestamp('last_login')->nullable();
            $table->string('password_reset', 5)->default('1');

            // Foreign Key Constraints
            // $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade')->onUpdate('cascade');
            // $table->foreign('approver_id')->references('id')->on('users')->onDelete('set null')->onUpdate('restrict');
            // $table->foreign('role_applied')->references('id')->on('roles')->onDelete('set null')->onUpdate('restrict');
            $table->foreign('organization_unit_id')->references('id')->on('organization_units')->onDelete('set null')->onUpdate('cascade');

            // Indexes
            $table->index('auth_token');
            $table->index('username');
            $table->index('email');
            $table->index('vendor_id');
            $table->index('approver_id');
            $table->index('role_applied');
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
