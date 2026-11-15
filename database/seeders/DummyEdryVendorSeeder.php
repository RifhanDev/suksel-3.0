<?php

namespace Database\Seeders;

use App\Role;
use App\Subscription;
use App\Support\VendorCidbMeta;
use App\User;
use App\Vendor;
use App\VendorCode;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * One ready-to-login dummy syarikat for Edry test login.
 *
 *   php artisan db:seed --class=DummyEdryVendorSeeder
 *
 * Email / username: edry@rifhan.com
 * Password:         Edry123!
 */
class DummyEdryVendorSeeder extends Seeder
{
    public const EMAIL = 'edry@rifhan.com';

    public const PASSWORD = 'Edry123!';

    public const COMPANY = 'Edry Rifhan Teknologi Sdn Bhd';

    public const REGISTRATION = 'EDRYTEST001';

    public function run(): void
    {
        $vendorRole = Role::query()->where('name', 'Vendor')->first();
        if (! $vendorRole) {
            $this->command?->error('Vendor role not found.');

            return;
        }

        $approverId = (int) (DB::table('users')
            ->whereNull('vendor_id')
            ->where('approved', 1)
            ->where('confirmed', 1)
            ->orderBy('id')
            ->value('id') ?? 1);

        $organizationUnitId = (int) (DB::table('organization_units')->orderBy('id')->value('id') ?? config('app.global_cart_ou') ?? 1);

        $result = DB::transaction(function () use ($vendorRole, $approverId, $organizationUnitId) {
            $user = User::query()
                ->where('email', self::EMAIL)
                ->orWhere('username', self::EMAIL)
                ->first();

            $vendor = null;
            if ($user?->vendor_id) {
                $vendor = Vendor::query()->find($user->vendor_id);
            }
            if (! $vendor) {
                $vendor = Vendor::query()->where('registration', self::REGISTRATION)->first() ?? new Vendor;
            }

            $vendor->fill([
                'registration' => $vendor->registration ?: self::REGISTRATION,
                'name' => self::COMPANY,
                'organization_type' => 'ROC: SENDIRIAN BERHAD',
                'address' => '1, Jalan Teknologi, Seksyen 14, 40000 Shah Alam, Selangor',
                'district_id' => $vendor->district_id ?: 8,
                'tel' => $vendor->tel ?: '+60121234567',
                'website' => $vendor->website ?: 'https://rifhan.com',
                'incorporation_date' => $vendor->incorporation_date ?: now()->subYears(5)->format('Y-m-d'),
                'authorized_capital' => $vendor->authorized_capital ?: 500000,
                'authorized_capital_currency' => 'MYR',
                'paidup_capital' => $vendor->paidup_capital ?: 250000,
                'paidup_capital_currency' => 'MYR',
                'bumi_percentage' => 100,
                'nonbumi_percentage' => 0,
                'foreigner_percentage' => 0,
                'mof_ref_no' => $vendor->mof_ref_no ?: 'MOF-EDRY001',
                'mof_start_date' => $vendor->mof_start_date ?: now()->subYear()->format('Y-m-d'),
                'mof_end_date' => $vendor->mof_end_date ?: now()->addYears(2)->format('Y-m-d'),
                'mof_bumi' => 1,
                'cidb_ref_no' => $vendor->cidb_ref_no ?: 'CIDB-EDRY001',
                'cidb_start_date' => $vendor->cidb_start_date ?: now()->subMonths(6)->format('Y-m-d'),
                'cidb_end_date' => $vendor->cidb_end_date ?: now()->addYear()->format('Y-m-d'),
                'cidb_bumi' => 1,
                'ssm_expiry' => $vendor->ssm_expiry ?: now()->addYears(2)->format('Y-m-d'),
                'officer_name' => 'Edry Suffian',
                'officer_designation' => 'Pengarah',
                'officer_email' => self::EMAIL,
                'officer_tel' => '0123456789',
                'token' => $vendor->token ?: Str::upper(Str::random(8)),
                'completed' => 1,
                'expiry_date' => now()->addYear()->format('Y-m-d'),
                'blacklisted_until' => '1970-01-01',
                'submission_date' => now()->subMonth()->format('Y-m-d'),
                'approval_1_id' => $vendor->approval_1_id ?: $approverId,
                'approval_date' => $vendor->approval_date ?: now()->subMonth()->format('Y-m-d'),
                'registration_paid' => 1,
                'organization_unit_id' => $vendor->organization_unit_id ?: $organizationUnitId,
                'state_id' => $vendor->state_id ?: 10,
            ]);

            if (empty($vendor->meta)) {
                $sections = VendorCidbMeta::dummySections();
                $sections[VendorCidbMeta::SECTION_MAKLUMAT_PENDAFTARAN_CIDB]['no_pendaftaran'] = $vendor->cidb_ref_no;
                $sections[VendorCidbMeta::SECTION_MAKLUMAT_SYARIKAT]['nama_syarikat'] = $vendor->name;
                $sections[VendorCidbMeta::SECTION_MAKLUMAT_SYARIKAT]['no_pendaftaran_ssm'] = $vendor->registration;
                $sections[VendorCidbMeta::SECTION_MAKLUMAT_SYARIKAT]['alamat_berdaftar'] = $vendor->address;
                $sections[VendorCidbMeta::SECTION_MAKLUMAT_SYARIKAT]['emel'] = self::EMAIL;
                $sections[VendorCidbMeta::SECTION_MAKLUMAT_SYARIKAT]['telefon'] = $vendor->tel;
                $vendor->meta = [
                    'source' => 'cidb',
                    'synced_at' => now()->toIso8601String(),
                    'synced_by' => null,
                    'sections' => $sections,
                ];
            }

            $vendor->save();

            $this->ensureSubscription((int) $vendor->id);
            $this->syncSampleCodes((int) $vendor->id);

            $user = User::withoutEvents(function () use ($user, $vendor) {
                $user = $user ?? new User;
                $user->fill([
                    'username' => self::EMAIL,
                    'email' => self::EMAIL,
                    'name' => 'Edry Suffian',
                    'tel' => $vendor->officer_tel,
                    'password' => Hash::make(self::PASSWORD),
                    'password_changed_at' => now(),
                    'confirmed' => 1,
                    'approved' => 1,
                    'vendor_id' => $vendor->id,
                    'organization_unit_id' => null,
                    'confirmation_code' => null,
                ]);
                $user->save();

                return $user;
            });

            DB::table('role_user')->updateOrInsert(
                ['user_id' => $user->id, 'role_id' => $vendorRole->id],
                []
            );

            try {
                if (! $user->hasRole('Vendor')) {
                    $user->assignRole('Vendor');
                }
            } catch (\Throwable) {
            }

            return [
                'user_id' => $user->id,
                'vendor_id' => $vendor->id,
                'registration' => $vendor->registration,
                'company' => $vendor->name,
                'can_participate' => $vendor->fresh()->canParticipateInTenders() ? 'yes' : 'no',
            ];
        });

        $this->command?->info('Dummy Edry vendor ready.');
        $this->command?->table(
            ['Email / Username', 'Password', 'Company', 'SSM', 'User ID', 'Vendor ID', 'Can participate'],
            [[
                self::EMAIL,
                self::PASSWORD,
                $result['company'],
                $result['registration'],
                $result['user_id'],
                $result['vendor_id'],
                $result['can_participate'],
            ]]
        );
    }

    protected function ensureSubscription(int $vendorId): void
    {
        if (! class_exists(Subscription::class)) {
            return;
        }

        $exists = Subscription::query()->where('vendor_id', $vendorId)->exists();
        if ($exists) {
            return;
        }

        try {
            $subscription = new Subscription;
            $subscription->fill([
                'vendor_id' => $vendorId,
                'start_date' => now()->subMonth()->format('Y-m-d'),
                'end_date' => now()->addYear()->format('Y-m-d'),
                'amount' => 0,
                'paid' => 1,
            ]);
            $subscription->save();
        } catch (\Throwable) {
            // Optional on older schemas.
        }
    }

    protected function syncSampleCodes(int $vendorId): void
    {
        $mof = DB::table('codes')->where('type', 'mof')->orderBy('id')->limit(3)->pluck('id');
        $cidb = DB::table('codes')->where('type', 'like', 'cidb%')->orderBy('id')->limit(3)->pluck('id');

        foreach ($mof as $codeId) {
            VendorCode::query()->firstOrCreate([
                'vendor_id' => $vendorId,
                'code_id' => $codeId,
                'code_type' => 'mof',
            ]);
        }

        foreach ($cidb as $codeId) {
            VendorCode::query()->firstOrCreate([
                'vendor_id' => $vendorId,
                'code_id' => $codeId,
                'code_type' => 'cidb-c',
            ]);
        }
    }
}
