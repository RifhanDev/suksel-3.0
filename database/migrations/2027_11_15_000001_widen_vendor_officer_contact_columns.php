<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('vendors')) {
            return;
        }

        // officer_email was varchar(45) while users.email is varchar(128).
        // On a strict SQL mode server the company row insert failed after the
        // user row was already stored, so the next attempt reported the email
        // as already used.
        DB::statement('ALTER TABLE vendors MODIFY officer_name VARCHAR(255) NULL');
        DB::statement('ALTER TABLE vendors MODIFY officer_designation VARCHAR(255) NULL');
        DB::statement('ALTER TABLE vendors MODIFY officer_email VARCHAR(255) NULL');
        DB::statement('ALTER TABLE vendors MODIFY officer_tel VARCHAR(64) NULL');
    }

    public function down(): void
    {
        if (! Schema::hasTable('vendors')) {
            return;
        }

        DB::statement('ALTER TABLE vendors MODIFY officer_name VARCHAR(45) NULL');
        DB::statement('ALTER TABLE vendors MODIFY officer_designation VARCHAR(45) NULL');
        DB::statement('ALTER TABLE vendors MODIFY officer_email VARCHAR(45) NULL');
        DB::statement('ALTER TABLE vendors MODIFY officer_tel VARCHAR(45) NULL');
    }
};
