<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Which penilaian stages ask for a 2FA code at their final Hantar. One row per peringkat.
        if (!Schema::hasTable('two_factor_penilaian_settings')) {
            Schema::create('two_factor_penilaian_settings', function (Blueprint $table) {
                $table->id();
                $table->string('peringkat')->unique(); // pembuka|cut_off|teknikal|kewangan
                $table->boolean('required')->default(false);
                $table->unsignedInteger('updated_by')->nullable(); // users.id is increments(), not bigIncrements()
                $table->timestamps();

                $table->foreign('updated_by', 'tfp_settings_updated_by_fk')->references('id')->on('users')->onDelete('set null');
            });

            foreach (['pembuka', 'cut_off', 'teknikal', 'kewangan'] as $peringkat) {
                DB::table('two_factor_penilaian_settings')->insert([
                    'peringkat'  => $peringkat,
                    'required'   => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Roles that get the code popup. A row exists = role ticked.
        if (!Schema::hasTable('two_factor_penilaian_roles')) {
            Schema::create('two_factor_penilaian_roles', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('role_id')->unique(); // roles.id is increments(), not bigIncrements()
                $table->boolean('semua_agensi')->default(true); // false = only the kategori/agensi picked below
                $table->unsignedInteger('updated_by')->nullable();
                $table->timestamps();

                $table->foreign('role_id', 'tfp_roles_role_id_fk')->references('id')->on('roles')->onDelete('cascade');
                $table->foreign('updated_by', 'tfp_roles_updated_by_fk')->references('id')->on('users')->onDelete('set null');
            });
        }

        // Kategori agensi (organization_types) a role covers, including agensi added later.
        if (!Schema::hasTable('two_factor_penilaian_role_kategori_agensi')) {
            Schema::create('two_factor_penilaian_role_kategori_agensi', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('role_id');
                $table->unsignedInteger('organization_type_id');
                $table->timestamps();

                $table->unique(['role_id', 'organization_type_id'], 'tfp_role_kategori_unique');
                $table->foreign('role_id', 'tfp_role_kategori_role_fk')->references('id')->on('roles')->onDelete('cascade');
                $table->foreign('organization_type_id', 'tfp_role_kategori_type_fk')->references('id')->on('organization_types')->onDelete('cascade');
            });
        }

        // Single agensi (organization_units) a role covers.
        if (!Schema::hasTable('two_factor_penilaian_role_agensi')) {
            Schema::create('two_factor_penilaian_role_agensi', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('role_id');
                $table->unsignedInteger('organization_unit_id');
                $table->timestamps();

                $table->unique(['role_id', 'organization_unit_id'], 'tfp_role_agensi_unique');
                $table->foreign('role_id', 'tfp_role_agensi_role_fk')->references('id')->on('roles')->onDelete('cascade');
                $table->foreign('organization_unit_id', 'tfp_role_agensi_unit_fk')->references('id')->on('organization_units')->onDelete('cascade');
            });
        }

        // One row per code accepted at a penilaian Hantar.
        if (!Schema::hasTable('two_factor_penilaian_logs')) {
            Schema::create('two_factor_penilaian_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('user_id');
                $table->unsignedInteger('tender_id')->nullable();
                $table->string('peringkat');
                $table->string('kaedah'); // totp|recovery
                $table->string('ip_address', 45)->nullable();
                $table->string('user_agent')->nullable();
                $table->timestamp('created_at')->nullable();

                $table->index(['tender_id', 'peringkat'], 'tfp_logs_tender_peringkat_idx');
                $table->foreign('user_id', 'tfp_logs_user_fk')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('tender_id', 'tfp_logs_tender_fk')->references('id')->on('tenders')->onDelete('set null');
            });
        }

        // Time step of the last accepted TOTP code, so each code works only once (login + penilaian).
        if (!Schema::hasColumn('two_factor_auths', 'last_used_step')) {
            Schema::table('two_factor_auths', function (Blueprint $table) {
                $table->unsignedInteger('last_used_step')->nullable()->after('locked_until');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('two_factor_auths', 'last_used_step')) {
            Schema::table('two_factor_auths', function (Blueprint $table) {
                $table->dropColumn('last_used_step');
            });
        }

        Schema::dropIfExists('two_factor_penilaian_logs');
        Schema::dropIfExists('two_factor_penilaian_role_agensi');
        Schema::dropIfExists('two_factor_penilaian_role_kategori_agensi');
        Schema::dropIfExists('two_factor_penilaian_roles');
        Schema::dropIfExists('two_factor_penilaian_settings');
    }
};
