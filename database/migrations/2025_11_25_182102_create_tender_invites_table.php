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
        if (Schema::hasTable('tender_invites')) {
            return;
        }
        Schema::create('tender_invites', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('tender_id');
            $table->integer('vendor_id');

            $table->index('tender_id');
            $table->index('vendor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tender_invites');
    }
};
