<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    public function up()
    {
        // Schema::create('vendors', function (Blueprint $table) {
        //     $table->id();

        //     $table->string('registration');
        //     $table->string('name');
        //     $table->string('organization_type');
        //     $table->text('address');
        //     $table->string('tel');
        //     $table->string('fax')->nullable();
        //     $table->string('website')->nullable();
        //     $table->date('incorporation_date');
        //     $table->decimal('authorized_capital', 15, 2)->nullable();
        //     $table->decimal('paidup_capital', 15, 2)->nullable();
        //     $table->string('authorized_capital_currency')->nullable();
        //     $table->string('paidup_capital_currency')->nullable();
        //     $table->string('gst_no')->nullable();
        //     $table->string('tax_no')->nullable();

        //     $table->float('bumi_percentage')->default(0);
        //     $table->float('nonbumi_percentage')->default(0);
        //     $table->float('foreigner_percentage')->default(0);

        //     $table->date('blacklisted_until')->nullable();
        //     $table->text('blacklist_reason')->nullable();

        //     $table->unsignedBigInteger('organization_unit_id')->nullable();
        //     $table->string('mof_ref_no')->nullable();
        //     $table->date('mof_start_date')->nullable();
        //     $table->date('mof_end_date')->nullable();
        //     $table->boolean('mof_bumi')->default(false);

        //     $table->string('cidb_ref_no')->nullable();
        //     $table->date('cidb_start_date')->nullable();
        //     $table->date('cidb_end_date')->nullable();
        //     $table->boolean('cidb_bumi')->default(false);

        //     $table->unsignedBigInteger('cidb_grade_id')->nullable();
        //     $table->unsignedBigInteger('cidb_grade_b_id')->nullable();
        //     $table->unsignedBigInteger('cidb_grade_ce_id')->nullable();
        //     $table->unsignedBigInteger('cidb_grade_me_id')->nullable();

        //     $table->date('ssm_expiry')->nullable();
        //     $table->date('submission_date')->nullable();
        //     $table->unsignedBigInteger('district_id')->nullable();

        //     $table->string('officer_name')->nullable();
        //     $table->string('officer_designation')->nullable();
        //     $table->string('officer_email')->nullable();
        //     $table->string('officer_tel')->nullable();

        //     $table->timestamp('certificate_generated_at')->nullable();

        //     $table->timestamps();

        //     // Optional foreign keys
        //     $table->foreign('organization_unit_id')->references('id')->on('organization_units')->onDelete('set null');
        //     // $table->foreign('district_id')->references('id')->on('districts')->onDelete('set null');
        // });

        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('registration', 128);
            $table->string('name', 255)->nullable();
            $table->string('organization_type', 128)->nullable();
            $table->text('address')->nullable();
            $table->integer('district_id')->nullable();
            $table->string('tel', 128)->nullable();
            $table->string('fax', 128)->nullable();
            $table->string('website', 128)->nullable();
            $table->date('incorporation_date')->nullable();
            $table->decimal('authorized_capital', 24, 2)->default(0.00);
            $table->string('authorized_capital_currency', 3)->nullable();
            $table->decimal('paidup_capital', 24, 2)->default(0.00);
            $table->string('paidup_capital_currency', 3)->nullable();
            $table->string('tax_no', 128)->nullable();
            $table->string('gst_no', 128)->nullable();
            $table->string('income_tax_no', 45)->nullable();
            $table->decimal('bumi_percentage', 6, 2)->unsigned()->default(0.00);
            $table->decimal('nonbumi_percentage', 6, 2)->unsigned()->default(0.00);
            $table->decimal('foreigner_percentage', 6, 2)->unsigned()->default(0.00);
            $table->string('mof_ref_no', 45)->nullable();
            $table->string('mof_start_date', 45)->nullable();
            $table->string('mof_end_date', 45)->nullable();
            $table->boolean('mof_bumi')->nullable();
            $table->string('cidb_ref_no', 45)->nullable();
            $table->string('cidb_start_date', 45)->nullable();
            $table->string('cidb_end_date', 45)->nullable();
            $table->boolean('cidb_bumi')->nullable();
            $table->integer('cidb_grade_id')->nullable();
            $table->integer('cidb_grade_b_id')->nullable();
            $table->integer('cidb_grade_ce_id')->nullable();
            $table->integer('cidb_grade_me_id')->nullable();
            $table->date('ssm_expiry')->nullable();
            $table->string('officer_name', 45);
            $table->string('officer_designation', 45);
            $table->string('officer_email', 45);
            $table->string('officer_tel', 45);
            $table->string('token', 128)->nullable();
            $table->boolean('completed')->default(0);
            $table->date('expiry_date')->default('1970-01-01');
            $table->date('blacklisted_until')->default('1970-01-01');
            $table->text('blacklist_reason')->nullable();
            $table->date('submission_date')->nullable();
            $table->unsignedBigInteger('approval_1_id')->nullable();
            $table->date('approval_date')->nullable();
            $table->boolean('registration_paid')->default(0);
            $table->text('rejection_reason')->nullable();
            $table->string('rejection_template_id', 255)->nullable();
            $table->boolean('smk')->default(0);
            $table->unsignedBigInteger('organization_unit_id');
            $table->timestamp('certificate_generated_at')->nullable();
            $table->timestamps();
            $table->unsignedInteger('state_id')->nullable();

            // Add indexes
            $table->foreign('approval_1_id')->references('id')->on('approvals')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('organization_unit_id')->references('id')->on('organization_units')->onDelete('cascade')->onUpdate('cascade');

            // Primary Key
            $table->primary('id');
            $table->index(['id', 'name'], 'vw_vdr_tran_idx01');
            $table->index('cidb_bumi');
            $table->index('approval_1_id', 'fk_vendors_approvals1_idx');
            $table->index('organization_unit_id', 'fk_vendors_organization_units1_idx');
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendors');
    }
}
