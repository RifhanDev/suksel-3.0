<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('transactions')) {
            return;
        }
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->string('number', 128)->nullable();
            $table->string('type', 128);
            $table->string('method', 45);

            $table->decimal('amount', 10, 2)->nullable();

            $table->boolean('claimed')->nullable()->default(0);

            $table->text('gateway_message')->nullable();
            $table->text('gateway_response')->nullable();
            $table->string('gateway_reference', 45)->nullable();
            $table->string('gateway_auth', 45)->nullable();
            $table->string('response_code', 45)->nullable();
            $table->text('response_message')->nullable();
            $table->string('ip', 128)->nullable();

            $table->string('status', 255)->default('success');

            $table->integer('fpx_job_status')->nullable()->default(0);
            $table->text('cached_data')->nullable();

            $table->unsignedInteger('organization_unit_id');
            $table->unsignedInteger('vendor_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('gateway_id')->nullable();

            $table->timestamp('receipt_generated_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('user_id', 'fk_transactions_users1_idx');
            $table->index('organization_unit_id', 'fk_transactions_organization_units1_idx');
            $table->index('gateway_id', 'transactions_gateway');
            $table->index('number', 'transactions_number');

            $table->index(
                [
                    'id',
                    'created_at',
                    'organization_unit_id',
                    'vendor_id',
                    'number',
                    'type',
                    'gateway_reference'
                ],
                'vw_vdr_tran_idx02'
            );

            $table->index(
                ['id', 'method', 'type', 'status', 'amount'],
                'vw_tndr_vdr_idx01'
            );

            $table->index(
                ['vendor_id', 'type', 'status'],
                'transaction_idx001'
            );

            // Foreign Keys
            $table->foreign('organization_unit_id', 'fk_transactions_organization_units1')
                ->references('id')->on('organization_units')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
