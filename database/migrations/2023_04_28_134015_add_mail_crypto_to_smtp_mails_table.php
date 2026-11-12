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
                if (Schema::hasTable('smtp_mails')) {
Schema::table('smtp_mails', function (Blueprint $table) {
            if (! Schema::hasColumn('smtp_mails', 'mail_crypto')) {
            $table->integer('mail_crypto')->nullable()->after('mail_port');
            }
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
        Schema::table('tenders', function (Blueprint $table) {
            //
        });
    }
};
