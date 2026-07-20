<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('vendor_blacklists')) {
            return;
        }
        Schema::create('vendor_blacklists', function (Blueprint $table) {
            $table->id();
            $table->string('reason', 45);
            $table->date('start');
            $table->date('end');
            $table->integer('vendor_id');
            $table->integer('organization_unit_id')->nullable();
            $table->integer('user_id');
            $table->string('status', 45);
            $table->text('cancel_reason')->nullable();
            $table->timestamps(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_blacklists');
    }
};
