<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttachmentsToMailQueuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('mail_queues') && ! Schema::hasColumn('mail_queues', 'attachments')) {
            Schema::table('mail_queues', function (Blueprint $table) {
                // JSON array of attachments: [{ filename, mime, data (base64) }, ...]
                // Nullable so existing/plain emails are unaffected.
                $table->longText('attachments')->nullable()->after('payload');
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
        Schema::table('mail_queues', function (Blueprint $table) {
            $table->dropColumn('attachments');
        });
    }
}
