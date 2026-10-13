<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;

/**
 * Rename role "Ketua Jabatan" → "Agency Ketua Jabatan".
 */
return new class extends Migration
{
    public function up(): void
    {
        $legacy = DB::table('roles')->where('name', 'Ketua Jabatan')->first();
        $canonical = DB::table('roles')->where('name', 'Agency Ketua Jabatan')->first();

        if ($legacy && !$canonical) {
            $payload = [
                'name' => 'Agency Ketua Jabatan',
                'updated_at' => now(),
            ];
            if (Schema::hasColumn('roles', 'display_name')) {
                $payload['display_name'] = 'Agency Ketua Jabatan';
            }
            if (Schema::hasColumn('roles', 'description')) {
                $payload['description'] = 'Agency Ketua Jabatan — Pemilihan Syarikat (Pembelian Terus / Lantikan Terus)';
            }
            DB::table('roles')->where('id', $legacy->id)->update($payload);
        } elseif ($legacy && $canonical) {
            // Move any user assignments from legacy to canonical, then drop legacy.
            if (Schema::hasTable('role_user')) {
                $legacyUserIds = DB::table('role_user')->where('role_id', $legacy->id)->pluck('user_id');
                foreach ($legacyUserIds as $userId) {
                    $exists = DB::table('role_user')
                        ->where('user_id', $userId)
                        ->where('role_id', $canonical->id)
                        ->exists();
                    if (!$exists) {
                        DB::table('role_user')->insert([
                            'user_id' => $userId,
                            'role_id' => $canonical->id,
                        ]);
                    }
                }
                DB::table('role_user')->where('role_id', $legacy->id)->delete();
            }

            if (Schema::hasTable('model_has_roles')) {
                $legacyRows = DB::table('model_has_roles')->where('role_id', $legacy->id)->get();
                foreach ($legacyRows as $row) {
                    $exists = DB::table('model_has_roles')
                        ->where('role_id', $canonical->id)
                        ->where('model_type', $row->model_type)
                        ->where('model_id', $row->model_id)
                        ->exists();
                    if (!$exists) {
                        $insert = [
                            'role_id' => $canonical->id,
                            'model_type' => $row->model_type,
                            'model_id' => $row->model_id,
                        ];
                        if (property_exists($row, 'team_id') || isset($row->team_id)) {
                            $insert['team_id'] = $row->team_id;
                        }
                        DB::table('model_has_roles')->insert($insert);
                    }
                }
                DB::table('model_has_roles')->where('role_id', $legacy->id)->delete();
            }

            DB::table('roles')->where('id', $legacy->id)->delete();
        }

        // Ensure permissions exist on the renamed role.
        (new \Database\Seeders\StosRolePermissionSeeder())->run();

        if (class_exists(PermissionRegistrar::class)) {
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }

    public function down(): void
    {
        $role = DB::table('roles')->where('name', 'Agency Ketua Jabatan')->first();
        if ($role) {
            $payload = [
                'name' => 'Ketua Jabatan',
                'updated_at' => now(),
            ];
            if (Schema::hasColumn('roles', 'display_name')) {
                $payload['display_name'] = 'Ketua Jabatan';
            }
            DB::table('roles')->where('id', $role->id)->update($payload);
        }
    }
};
