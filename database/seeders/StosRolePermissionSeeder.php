<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;

class StosRolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $timestamp = now();

        $this->renameLegacyCommitteeRole();
        $this->upsertRoles($timestamp);
        $this->upsertPermissions($timestamp);
        $this->attachRolePermissions($timestamp);
        $this->forgetPermissionCache();
    }

    public function rollback(): void
    {
        $this->detachSeededPermissions();

        $permissionNames = array_column($this->newPermissions(), 'name');
        if ($permissionNames !== []) {
            DB::table('permissions')->whereIn('name', $permissionNames)->delete();
        }

        $roleNames = array_column($this->newRoles(), 'name');
        if ($roleNames !== []) {
            DB::table('roles')->whereIn('name', $roleNames)->delete();
        }

        $this->forgetPermissionCache();
    }

    private function renameLegacyCommitteeRole(): void
    {
        $legacy = DB::table('roles')->where('name', 'Jawatankuasa')->first();
        $canonical = DB::table('roles')->where('name', 'Agency Jawatankuasa')->first();

        if ($legacy && !$canonical) {
            $payload = ['name' => 'Agency Jawatankuasa', 'updated_at' => now()];
            if (Schema::hasColumn('roles', 'display_name')) {
                $payload['display_name'] = 'Agency Jawatankuasa';
            }
            DB::table('roles')->where('id', $legacy->id)->update($payload);
        }
    }

    private function upsertRoles($timestamp): void
    {
        $columns = $this->tableColumns('roles');

        foreach ($this->newRoles() as $role) {
            $exists = DB::table('roles')->where('name', $role['name'])->exists();
            if ($exists) {
                continue;
            }

            DB::table('roles')->insert($this->filterColumns([
                'name' => $role['name'],
                'guard_name' => 'web',
                'display_name' => $role['display_name'] ?? $role['name'],
                'description' => $role['description'] ?? null,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ], $columns));
        }
    }

    private function upsertPermissions($timestamp): void
    {
        $columns = $this->tableColumns('permissions');

        foreach ($this->newPermissions() as $permission) {
            $exists = DB::table('permissions')->where('name', $permission['name'])->exists();
            if ($exists) {
                continue;
            }

            DB::table('permissions')->insert($this->filterColumns([
                'name' => $permission['name'],
                'group_name' => $permission['group_name'] ?? null,
                'guard_name' => 'web',
                'display_name' => $permission['display_name'] ?? $permission['name'],
                'description' => $permission['description'] ?? null,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ], $columns));
        }
    }

    private function attachRolePermissions($timestamp): void
    {
        $syncLegacy = Schema::hasTable('permission_role');
        $syncSpatie = Schema::hasTable('role_has_permissions');
        $legacyColumns = $syncLegacy ? $this->tableColumns('permission_role') : [];
        $spatieColumns = $syncSpatie ? $this->tableColumns('role_has_permissions') : [];

        foreach ($this->rolePermissions() as $mapping) {
            $role = DB::table('roles')->where('name', $mapping['role'])->first();
            if (!$role) {
                continue;
            }

            foreach ($mapping['permissions'] as $permissionName) {
                $permission = DB::table('permissions')->where('name', $permissionName)->first();
                if (!$permission) {
                    continue;
                }

                if ($syncLegacy) {
                    $exists = DB::table('permission_role')
                        ->where('permission_id', $permission->id)
                        ->where('role_id', $role->id)
                        ->exists();

                    if (!$exists) {
                        DB::table('permission_role')->insert($this->filterColumns([
                            'permission_id' => $permission->id,
                            'role_id' => $role->id,
                            'created_at' => $timestamp,
                            'updated_at' => $timestamp,
                        ], $legacyColumns));
                    }
                }

                if ($syncSpatie) {
                    $exists = DB::table('role_has_permissions')
                        ->where('permission_id', $permission->id)
                        ->where('role_id', $role->id)
                        ->exists();

                    if (!$exists) {
                        DB::table('role_has_permissions')->insert($this->filterColumns([
                            'permission_id' => $permission->id,
                            'role_id' => $role->id,
                        ], $spatieColumns));
                    }
                }
            }
        }
    }

    private function detachSeededPermissions(): void
    {
        $syncLegacy = Schema::hasTable('permission_role');
        $syncSpatie = Schema::hasTable('role_has_permissions');
        $permissionNames = array_values(array_unique(array_merge(
            array_column($this->newPermissions(), 'name'),
            ['Tender:execute', 'Tender:list', 'Tender:specification-management', 'specification:create']
        )));

        foreach ($this->rolePermissions() as $mapping) {
            $role = DB::table('roles')->where('name', $mapping['role'])->first();
            if (!$role) {
                continue;
            }

            $permissionIds = DB::table('permissions')->whereIn('name', $permissionNames)->pluck('id');
            if ($permissionIds->isEmpty()) {
                continue;
            }

            if ($syncLegacy) {
                DB::table('permission_role')
                    ->where('role_id', $role->id)
                    ->whereIn('permission_id', $permissionIds)
                    ->delete();
            }

            if ($syncSpatie) {
                DB::table('role_has_permissions')
                    ->where('role_id', $role->id)
                    ->whereIn('permission_id', $permissionIds)
                    ->delete();
            }
        }
    }

    private function forgetPermissionCache(): void
    {
        if (class_exists(PermissionRegistrar::class)) {
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }

    private function tableColumns(string $table): array
    {
        return collect(DB::select(
            'SELECT COLUMN_NAME FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?',
            [$table]
        ))->pluck('COLUMN_NAME')->map(fn ($name) => (string) $name)->all();
    }

    private function filterColumns(array $row, array $columns): array
    {
        return array_intersect_key($row, array_flip($columns));
    }

    private function newRoles(): array
    {
        return [
            ['name' => 'Agency Urusetia', 'display_name' => 'Agency Urusetia'],
            ['name' => 'Agency Jawatankuasa', 'display_name' => 'Agency Jawatankuasa'],
            ['name' => 'Agency Lembaga Perolehan', 'display_name' => 'Agency Lembaga Perolehan'],
            ['name' => 'Lembaga Perolehan Negeri Selangor', 'display_name' => 'Lembaga Perolehan Negeri Selangor'],
        ];
    }

    private function newPermissions(): array
    {
        return [
            ['name' => 'tender:specification-management', 'group_name' => 'Tender', 'display_name' => 'Manage Tender specification (legacy)'],
            ['name' => 'Advertisement:list', 'group_name' => 'Tender', 'display_name' => 'Penyediaan Iklan'],
            ['name' => 'SiteVisit:list', 'group_name' => 'Tender', 'display_name' => 'Lawatan Tapak'],
            ['name' => 'DepartmentCertification:list', 'group_name' => 'Tender', 'display_name' => 'Perakuan Jabatan'],
            ['name' => 'MeetingDecision:list', 'group_name' => 'Tender', 'display_name' => 'Keputusan Mesyuarat'],
            ['name' => 'LetterOfIntent:list', 'group_name' => 'Tender', 'display_name' => 'Penyediaan Surat Niat'],
            ['name' => 'SST:list', 'group_name' => 'Tender', 'display_name' => 'Penyediaan SST'],
            ['name' => 'Meeting:list', 'group_name' => 'Meeting', 'display_name' => 'Perincian Mesyuarat'],
            ['name' => 'MeetingAttendance:list', 'group_name' => 'Meeting', 'display_name' => 'Kehadiran Mesyuarat'],
            ['name' => 'OpenerEvaluation:list', 'group_name' => 'Evaluation', 'display_name' => 'Penilaian Pembuka'],
            ['name' => 'CutOff:list', 'group_name' => 'Evaluation', 'display_name' => 'Cut-Off'],
            ['name' => 'TechnicalEvaluation:list', 'group_name' => 'Evaluation', 'display_name' => 'Penilaian Teknikal'],
            ['name' => 'FinancialEvaluation:list', 'group_name' => 'Evaluation', 'display_name' => 'Penilaian Kewangan'],
            ['name' => 'DirectPurchase:list', 'group_name' => 'DirectPurchase', 'display_name' => 'Pembelian Terus'],
            ['name' => 'DirectAppointment:list', 'group_name' => 'DirectAppointment', 'display_name' => 'Lantikan Terus'],
            ['name' => 'Bidding:list', 'group_name' => 'Bidding', 'display_name' => 'Bidaan'],
        ];
    }

    private function adminOnlyNewPermissions(): array
    {
        return array_column($this->newPermissions(), 'name');
    }

    private function rolePermissions(): array
    {
        $urusetia = [
            'Tender:list',
            // Urusetia yang menjalankan proses pelantikan jawatankuasa, jadi
            // butang "Lantik Jawatan Kuasa" pada senarai tender mesti terbuka
            // kepada mereka. Tanpa ini kolum Tindakan kekal kosong dan aliran
            // kerja tersekat pada peringkat kedua.
            'committee:create',
            'Advertisement:list',
            'SiteVisit:list',
            'DepartmentCertification:list',
            'LetterOfIntent:list',
            'SST:list',
            'Meeting:list',
            'MeetingAttendance:list',
            'CutOff:list',
        ];

        $jawatankuasa = [
            'Tender:specification-management',
            'tender:specification-management',
            'specification:create',
            'OpenerEvaluation:list',
            'CutOff:list',
            'TechnicalEvaluation:list',
            'FinancialEvaluation:list',
        ];

        $lembaga = [
            'MeetingDecision:list',
        ];

        return [
            [
                'role' => 'Admin',
                'permissions' => array_values(array_unique(array_merge(
                    $this->adminOnlyNewPermissions(),
                    ['Tender:execute', 'Tender:list', 'Tender:specification-management', 'specification:create']
                ))),
            ],
            [
                'role' => 'Agency User',
                'permissions' => [
                    'Tender:execute',
                    'Tender:list',
                ],
            ],
            [
                'role' => 'Agency Urusetia',
                'permissions' => $urusetia,
            ],
            [
                'role' => 'Agency Jawatankuasa',
                'permissions' => $jawatankuasa,
            ],
            [
                'role' => 'Jawatan Kuasa Spesifikasi',
                'permissions' => [
                    'Tender:specification-management',
                    'tender:specification-management',
                    'specification:create',
                ],
            ],
            [
                'role' => 'Agency Lembaga Perolehan',
                'permissions' => $lembaga,
            ],
            [
                'role' => 'Lembaga Perolehan Negeri Selangor',
                'permissions' => $lembaga,
            ],
        ];
    }
}
