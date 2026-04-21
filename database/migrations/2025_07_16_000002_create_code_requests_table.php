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
        if (Schema::hasTable('code_requests')) {
            return;
        }
        Schema::create('code_requests', function (Blueprint $table) {
            $table->id();
            $table->string('type', 128);
            $table->text('data')->nullable();
            $table->unsignedBigInteger('approval_1_id')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->string('rejection_template_id', 255)->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('vendor_id');
            $table->string('status', 20)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            // Add indexes
            $table->index('vendor_id', 'fk_change_requests_vendors1_idx');
            $table->index('user_id', 'fk_change_requests_users1_idx');
            $table->index('approval_1_id', 'fk_change_requests_approvals1_idx');

            // Add foreign key constraints
            $table->foreign('approval_1_id')->references('id')->on('approvals')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            // Primary Key
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('code_requests');
    }
};
