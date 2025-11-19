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
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->integer('transaction_id')->nullable();
            $table->string('number')->nullable();
            $table->string('name')->nullable();
            $table->string('ic')->nullable();
            $table->string('tel')->nullable();
            $table->string('address')->nullable();
            $table->integer('bank_id')->nullable();
            $table->string('bank_acc')->nullable();
            $table->string('bank_address')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('remark')->nullable();
            $table->integer('status')->default(0);
            $table->string('rejection_reason')->nullable();
            $table->string('rejection_template_id')->nullable();
            $table->integer('vendor_id')->nullable();
            $table->integer('user_id')->nullable();
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
        Schema::dropIfExists('refunds');
    }
};
