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
        if (Schema::hasTable('tender_eligibles')) {
            return;
        }
        Schema::create('tender_eligibles', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('tender_id');
            $table->integer('vendor_id');
            $table->tinyInteger('email')->default(0);

            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('sent_at')->nullable();

            $table->index('sent_at');
            $table->index('tender_id');
            $table->index('vendor_id');
            $table->index('email');
            $table->index('created_at');

            $table->index(['email', 'sent_at', 'created_at'], 'tender_eligibles_idx01');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tender_eligibles');
    }
};
