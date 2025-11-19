<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();

            $table->string('registration');
            $table->string('name');
            $table->string('organization_type');
            $table->text('address');
            $table->string('tel');
            $table->string('fax')->nullable();
            $table->string('website')->nullable();
            $table->date('incorporation_date');
            $table->decimal('authorized_capital', 15, 2)->nullable();
            $table->decimal('paidup_capital', 15, 2)->nullable();
            $table->string('authorized_capital_currency')->nullable();
            $table->string('paidup_capital_currency')->nullable();
            $table->string('gst_no')->nullable();
            $table->string('tax_no')->nullable();

            $table->float('bumi_percentage')->default(0);
            $table->float('nonbumi_percentage')->default(0);
            $table->float('foreigner_percentage')->default(0);

            $table->date('blacklisted_until')->nullable();
            $table->text('blacklist_reason')->nullable();

            $table->unsignedBigInteger('organization_unit_id')->nullable();
            $table->string('mof_ref_no')->nullable();
            $table->date('mof_start_date')->nullable();
            $table->date('mof_end_date')->nullable();
            $table->boolean('mof_bumi')->default(false);

            $table->string('cidb_ref_no')->nullable();
            $table->date('cidb_start_date')->nullable();
            $table->date('cidb_end_date')->nullable();
            $table->boolean('cidb_bumi')->default(false);

            $table->unsignedBigInteger('cidb_grade_id')->nullable();
            $table->unsignedBigInteger('cidb_grade_b_id')->nullable();
            $table->unsignedBigInteger('cidb_grade_ce_id')->nullable();
            $table->unsignedBigInteger('cidb_grade_me_id')->nullable();

            $table->date('ssm_expiry')->nullable();
            $table->date('submission_date')->nullable();
            $table->unsignedBigInteger('district_id')->nullable();

            $table->string('officer_name')->nullable();
            $table->string('officer_designation')->nullable();
            $table->string('officer_email')->nullable();
            $table->string('officer_tel')->nullable();

            $table->timestamp('certificate_generated_at')->nullable();

            $table->timestamps();

            // Optional foreign keys
            $table->foreign('organization_unit_id')->references('id')->on('organization_units')->onDelete('set null');
            // $table->foreign('district_id')->references('id')->on('districts')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendors');
    }
}
