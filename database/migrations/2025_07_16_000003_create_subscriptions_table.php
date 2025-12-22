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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();

            $table->date('start_date');
            $table->date('end_date');

            $table->boolean('renewal')->nullable()->default(0);

            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('vendor_id');

            $table->timestamps();

            $table->index('vendor_id', 'fk_subscriptions_vendors1_idx');
            $table->index('transaction_id', 'fk_subscriptions_transactions1_idx');

            $table->foreign('transaction_id', 'fk_subscriptions_transactions1')
                ->references('id')->on('transactions')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('vendor_id', 'fk_subscriptions_vendors1')
                ->references('id')->on('vendors')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
