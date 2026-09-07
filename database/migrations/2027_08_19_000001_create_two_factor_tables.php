<?php

use App\Support\SchemaCompat;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Per-user TOTP state. Kept off the `users` table so the secret stays out
        // of User's mass-assignment surface.
        SchemaCompat::dropIfIncomplete('two_factor_auths');
        if (! Schema::hasTable('two_factor_auths')) {
            Schema::create('two_factor_auths', function (Blueprint $table) {
                $table->id();
                SchemaCompat::referenceColumn($table, 'user_id', 'users');
                $table->text('secret')->nullable(); // encrypted cast on the model
                $table->timestamp('confirmed_at')->nullable(); // null = enrolment started but never confirmed
                $table->timestamp('required_since')->nullable(); // anchors the grace-period countdown
                $table->unsignedTinyInteger('failed_attempts')->default(0);
                $table->timestamp('locked_until')->nullable();
                $table->string('remember_token')->nullable(); // hashed "remember this device" token
                $table->timestamp('remember_expires_at')->nullable();
                $table->timestamps();

                $table->unique('user_id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        SchemaCompat::dropIfIncomplete('two_factor_recovery_codes');
        if (! Schema::hasTable('two_factor_recovery_codes')) {
            Schema::create('two_factor_recovery_codes', function (Blueprint $table) {
                $table->id();
                SchemaCompat::referenceColumn($table, 'user_id', 'users');
                $table->string('code_hash');
                $table->timestamp('used_at')->nullable();
                $table->timestamp('created_at')->nullable();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->index(['user_id', 'used_at']);
            });
        }

        // One row per role that has ever been toggled. Absence of a row = not required.
        SchemaCompat::dropIfIncomplete('two_factor_role_settings');
        if (! Schema::hasTable('two_factor_role_settings')) {
            Schema::create('two_factor_role_settings', function (Blueprint $table) {
                $table->id();
                SchemaCompat::referenceColumn($table, 'role_id', 'roles');
                $table->boolean('required')->default(false);
                SchemaCompat::referenceColumn($table, 'updated_by', 'users', true);
                $table->timestamps();

                $table->unique('role_id');
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            });
        }

        // Global knobs. Singleton row (id = 1), seeded below.
        SchemaCompat::dropIfIncomplete('two_factor_settings');
        if (! Schema::hasTable('two_factor_settings')) {
            Schema::create('two_factor_settings', function (Blueprint $table) {
                $table->id();
                $table->unsignedSmallInteger('grace_period_days')->default(7);
                $table->unsignedTinyInteger('recovery_codes_count')->default(8);
                $table->unsignedTinyInteger('max_failed_attempts')->default(5);
                $table->unsignedSmallInteger('lockout_minutes')->default(5);
                $table->unsignedSmallInteger('remember_device_days')->default(30);
                SchemaCompat::referenceColumn($table, 'updated_by', 'users', true);
                $table->timestamps();

                $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            });

            DB::table('two_factor_settings')->insert([
                'id' => 1,
                'grace_period_days' => 7,
                'recovery_codes_count' => 8,
                'max_failed_attempts' => 5,
                'lockout_minutes' => 5,
                'remember_device_days' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Dedicated audit trail. UserHistory::log() drops its actor id before saving,
        // so it cannot record who performed an admin reset.
        SchemaCompat::dropIfIncomplete('two_factor_audit_logs');
        if (! Schema::hasTable('two_factor_audit_logs')) {
            Schema::create('two_factor_audit_logs', function (Blueprint $table) {
                $table->id();
                SchemaCompat::referenceColumn($table, 'user_id', 'users');
                SchemaCompat::referenceColumn($table, 'actor_id', 'users', true);
                $table->string('event'); // enrolled|disabled|admin_reset|recovery_used|locked_out|role_requirement_toggled
                $table->json('meta')->nullable();
                $table->timestamp('created_at')->nullable();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('actor_id')->references('id')->on('users')->onDelete('set null');
                $table->index(['user_id', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('two_factor_audit_logs');
        Schema::dropIfExists('two_factor_settings');
        Schema::dropIfExists('two_factor_role_settings');
        Schema::dropIfExists('two_factor_recovery_codes');
        Schema::dropIfExists('two_factor_auths');
    }
};
