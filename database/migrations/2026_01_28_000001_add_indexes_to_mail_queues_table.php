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
        Schema::table('mail_queues', function (Blueprint $table) {
            // Indexes to speed up filtering and ordering in MailQueueController
            if (!Schema::hasColumn('mail_queues', 'created_at')) {
                return;
            }

            $table->index('created_at', 'mail_queues_created_at_index');
            $table->index('status', 'mail_queues_status_index');
            $table->index('email_send_at', 'mail_queues_email_send_at_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mail_queues', function (Blueprint $table) {
            $table->dropIndex('mail_queues_created_at_index');
            $table->dropIndex('mail_queues_status_index');
            $table->dropIndex('mail_queues_email_send_at_index');
        });
    }
};

