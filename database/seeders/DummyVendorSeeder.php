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
 * Creates 20 fully usable dummy vendor accounts (login + participate).
 *
 * Run on any environment:
 *   php artisan db:seed --class=DummyVendorSeeder
 *
 * Default password for all: Vendor@12345
 */
class DummyVendorSeeder extends Seeder
{
    public const PASSWORD = 'Vendor@12345';

    public const EMAIL_DOMAIN = 'dummy.stos.local';

    /**
     * @var list<array{slug: string, company: string, officer: string}>
     */
    private array $vendors = [
        ['slug' => 'vendor01', 'company' => 'Maju Jaya Trading Sdn Bhd', 'officer' => 'Ahmad Fauzi'],
        ['slug' => 'vendor02', 'company' => 'Selangor Supply Hub Sdn Bhd', 'officer' => 'Siti Aminah'],
        ['slug' => 'vendor03', 'company' => 'Delta Perkasa Enterprise', 'officer' => 'Rajesh Kumar'],
        ['slug' => 'vendor04', 'company' => 'Cahaya Teknikal Sdn Bhd', 'officer' => 'Lim Wei Ming'],
        ['slug' => 'vendor05', 'company' => 'Nilai Bestari Resources', 'officer' => 'Nurul Huda'],
        ['slug' => 'vendor06', 'company' => 'Putra Niaga Global Sdn Bhd', 'officer' => 'Hafiz Rahman'],
        ['slug' => 'vendor07', 'company' => 'Seri Mentari Solutions', 'officer' => 'Farah Izzati'],
        ['slug' => 'vendor08', 'company' => 'Bayu Kontraktor Sdn Bhd', 'officer' => 'Mohd Faizal'],
        ['slug' => 'vendor09', 'company' => 'Anggun Office Mart Sdn Bhd', 'officer' => 'Tan Mei Ling'],
        ['slug' => 'vendor10', 'company' => 'Wawasan ICT Services', 'officer' => 'Azman Ismail'],
        ['slug' => 'vendor11', 'company' => 'Harmoni Facility Care Sdn Bhd', 'officer' => 'Zuliana Kassim'],
        ['slug' => 'vendor12', 'company' => 'Tekad Infrastruktur Sdn Bhd', 'officer' => 'Chong Kok Wai'],
        ['slug' => 'vendor13', 'company' => 'Alam Hijau Landskap Sdn Bhd', 'officer' => 'Khairul Anuar'],
        ['slug' => 'vendor14', 'company' => 'Metro Print & Media Sdn Bhd', 'officer' => 'Priya Nair'],
        ['slug' => 'vendor15', 'company' => 'Sentosa Electrical Works', 'officer' => 'Lee Chee Keong'],
        ['slug' => 'vendor16', 'company' => 'Rimba Security Services Sdn Bhd', 'officer' => 'Amirul Hakim'],
        ['slug' => 'vendor17', 'company' => 'Puncak Logistik Express', 'officer' => 'Yasmin Abdullah'],
        ['slug' => 'vendor18', 'company' => 'Orkid Cleaning Specialist Sdn Bhd', 'officer' => 'Goh Wei Jie'],
        ['slug' => 'vendor19', 'company' => 'Sinar Medical Supplies Sdn Bhd', 'officer' => 'Dr. Aina Sofea'],
        ['slug' => 'vendor20', 'company' => 'Lagenda Furniture Hub Sdn Bhd', 'officer' => 'Firdaus Zakaria'],
    ];

    public function run(): void
    {
        $vendorRole = Role::query()->where('name', 'Vendor')->first();
        if (! $vendorRole) {
            $this->command?->error('DummyVendorSeeder: Vendor role not found. Run StosRolePermissionSeeder / RoleSeeder first.');

            return;
        }

        $approverId = (int) (DB::table('users')
            ->whereNull('vendor_id')
            ->where('approved', 1)
            ->where('confirmed', 1)
            ->orderBy('id')
            ->value('id') ?? 1);

        $organizationUnitId = DB::table('organization_units')->orderBy('id')->value('id');
        $mofCodeIds = $this->resolveMofCodeIds();
        $cidbCodeIds = $this->resolveCidbCodeIds();

        $rows = [];

        foreach ($this->vendors as $index => $item) {
            $email = $item['slug'].'@'.self::EMAIL_DOMAIN;
            $registration = sprintf('DUMMY%04d', $index + 1);

            DB::transaction(function () use (
                $item,
                $email,
                $registration,
                $approverId,
                $organizationUnitId,
                $vendorRole,
                $mofCodeIds,
                $cidbCodeIds,
                $index,
                &$rows
            ) {
                $vendor = Vendor::query()->where('registration', $registration)->first();

                if (! $vendor) {
                    $existingUser = User::query()->where('email', $email)->first();
                    if ($existingUser?->vendor_id) {
                        $vendor = Vendor::query()->find($existingUser->vendor_id);
                    }
                }

                if (! $vendor) {
                    $vendor = new Vendor;
                }

                $vendor->fill([
                    'registration' => $registration,
                    'name' => $item['company'],
                    'organization_type' => 'ROC: SENDIRIAN BERHAD',
                    'address' => ($index + 1).', Jalan Dummy STOS, Seksyen 14, 40000 Shah Alam, Selangor',
                    'district_id' => 8,
                    'tel' => sprintf('+6012%07d', 3000000 + $index + 1),
                    'website' => 'https://'.$item['slug'].'.example',
                    'incorporation_date' => now()->subYears(5)->format('Y-m-d'),
                    'authorized_capital' => 500000,
                    'authorized_capital_currency' => 'MYR',
                    'paidup_capital' => 250000,
                    'paidup_capital_currency' => 'MYR',
                    'tax_no' => 'TAX-'.$registration,
                    'gst_no' => 'GST-'.$registration,
                    'bumi_percentage' => 100,
                    'nonbumi_percentage' => 0,
                    'foreigner_percentage' => 0,
                    'mof_ref_no' => 'MOF-'.$registration,
                    'mof_start_date' => now()->subYear()->format('Y-m-d'),
                    'mof_end_date' => now()->addYears(2)->format('Y-m-d'),
                    'mof_bumi' => 1,
                    'cidb_ref_no' => 'CIDB-'.$registration,
                    'cidb_start_date' => now()->subMonths(6)->format('Y-m-d'),
                    'cidb_end_date' => now()->addYear()->format('Y-m-d'),
                    'cidb_bumi' => 1,
                    'ssm_expiry' => now()->addYears(2)->format('Y-m-d'),
                    'officer_name' => $item['officer'],
                    'officer_designation' => 'Pengarah',
                    'officer_email' => $email,
                    'officer_tel' => sprintf('012%07d', 3000000 + $index + 1),
                    'token' => Str::upper(Str::random(8)),
                    'completed' => 1,
                    'expiry_date' => now()->addYear()->format('Y-m-d'),
                    'blacklisted_until' => '1970-01-01',
                    'submission_date' => now()->subMonth()->format('Y-m-d'),
                    'approval_1_id' => $approverId,
                    'approval_date' => now()->subMonth()->format('Y-m-d'),
                    'registration_paid' => 1,
                    'organization_unit_id' => $organizationUnitId,
                    'state_id' => 10,
                ]);

                if (empty($vendor->meta)) {
                    $sections = VendorCidbMeta::dummySections();
                    $sections[VendorCidbMeta::SECTION_MAKLUMAT_PENDAFTARAN_CIDB]['no_pendaftaran'] = $vendor->cidb_ref_no;
                    $sections[VendorCidbMeta::SECTION_MAKLUMAT_SYARIKAT]['nama_syarikat'] = $vendor->name;
                    $sections[VendorCidbMeta::SECTION_MAKLUMAT_SYARIKAT]['no_pendaftaran_ssm'] = $vendor->registration;
                    $sections[VendorCidbMeta::SECTION_MAKLUMAT_SYARIKAT]['alamat_berdaftar'] = $vendor->address;
                    $sections[VendorCidbMeta::SECTION_MAKLUMAT_SYARIKAT]['emel'] = $email;
                    $sections[VendorCidbMeta::SECTION_MAKLUMAT_SYARIKAT]['telefon'] = $vendor->tel;

                    $syncedAt = now()->toIso8601String();
                    $vendor->meta = [
                        'source' => 'cidb',
                        'synced_at' => $syncedAt,
                        'synced_by' => null,
                        'sections' => $sections,
                    ];
                }

                $vendor->save();

                $this->syncVendorCodes($vendor->id, 'mof', $mofCodeIds);
                $this->syncVendorCodes($vendor->id, 'cidb-c', $cidbCodeIds);
                $this->ensureSubscription($vendor->id);

                // Skip User::created history hook — staging/prod user_histories FK can point at legacy users1.
                $user = User::withoutEvents(function () use ($email, $item, $vendor) {
                    $user = User::query()->where('email', $email)->first() ?? new User;
                    $user->fill([
                        'username' => $email,
                        'email' => $email,
                        'name' => $item['officer'],
                        'tel' => $vendor->officer_tel,
                        'password' => Hash::make(self::PASSWORD),
                        'password_changed_at' => now(),
                        'confirmed' => 1,
                        'approved' => 1,
                        'vendor_id' => $vendor->id,
                        'organization_unit_id' => null,
                        'confirmation_code' => null,
                    ]);
                    // two_factor_code / two_factor_expires_at sengaja tidak
                    // ditetapkan di sini. Lajur itu tidak wujud pada pangkalan
                    // data yang dipulihkan daripada 2.0 — migration yang
                    // sepatutnya menambahnya (2025_12_24_195304) dikomen
                    // sepenuhnya, jadi ia tidak pernah dicipta di mana-mana
                    // pelayan. Tiada apa dalam aplikasi membacanya pula; ciri
                    // 2FA sebenar menggunakan jadual two_factor_* berasingan.
                    // Menetapkannya kepada null hanyalah nilai lalai, jadi dua
                    // baris itu tidak melakukan apa-apa selain menggagalkan
                    // seeder dengan ralat 1054.
                    $user->save();

                    return $user;
                });

                DB::table('role_user')->updateOrInsert(
                    [
                        'user_id' => $user->id,
                        'role_id' => $vendorRole->id,
                    ],
                    []
                );

                try {
                    if (! $user->hasRole('Vendor')) {
                        $user->assignRole('Vendor');
                    }
                } catch (\Throwable $e) {
                    // role_user row above is enough for this app's HasRoles override
                }

                $rows[] = [
                    'email' => $email,
                    'company' => $item['company'],
                    'vendor_id' => $vendor->id,
                    'user_id' => $user->id,
                    'can_participate' => $vendor->fresh()->canParticipateInTenders() ? 'yes' : 'no',
                ];
            });
        }

        $this->command?->info('DummyVendorSeeder: '.count($rows).' vendors ready.');
        $this->command?->info('Password for all: '.self::PASSWORD);
        $this->command?->table(
            ['#', 'Email', 'Company', 'Vendor ID', 'User ID', 'Can participate'],
            collect($rows)->values()->map(fn ($row, $i) => [
                $i + 1,
                $row['email'],
                $row['company'],
                $row['vendor_id'],
                $row['user_id'],
                $row['can_participate'],
            ])->all()
        );
    }

    /**
     * @return list<int>
     */
    private function resolveMofCodeIds(): array
    {
        $preferred = [1, 5, 14, 42, 43, 44, 45, 70, 168, 217, 222, 226, 228, 231, 234, 274, 288, 302, 374, 377];
        $existing = DB::table('codes')
            ->where('type', 'mof')
            ->whereIn('id', $preferred)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        if (count($existing) >= 5) {
            return $existing;
        }

        return DB::table('codes')
            ->where('type', 'mof')
            ->orderBy('id')
            ->limit(15)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    /**
     * @return list<int>
     */
    private function resolveCidbCodeIds(): array
    {
        return DB::table('codes')
            ->where('type', 'cidb-c')
            ->orderBy('id')
            ->limit(5)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    /**
     * @param  list<int>  $codeIds
     */
    private function syncVendorCodes(int $vendorId, string $codeType, array $codeIds): void
    {
        foreach ($codeIds as $codeId) {
            $exists = VendorCode::query()
                ->where('vendor_id', $vendorId)
                ->where('code_type', $codeType)
                ->where('code_id', $codeId)
                ->exists();

            if ($exists) {
                continue;
            }

            VendorCode::query()->create([
                'vendor_id' => $vendorId,
                'code_type' => $codeType,
                'code_id' => $codeId,
            ]);
        }
    }

    private function ensureSubscription(int $vendorId): void
    {
        $subscription = Subscription::query()
            ->where('vendor_id', $vendorId)
            ->orderByDesc('id')
            ->first();

        $start = now()->subMonth()->format('Y-m-d');
        $end = now()->addYear()->format('Y-m-d');
        $transactionId = $this->ensureRegistrationTransaction($vendorId);

        if ($subscription) {
            $subscription->start_date = $start;
            $subscription->end_date = $end;
            $subscription->renewal = 0;
            $subscription->transaction_id = $transactionId;
            $subscription->save();

            return;
        }

        Subscription::query()->create([
            'vendor_id' => $vendorId,
            'start_date' => $start,
            'end_date' => $end,
            'renewal' => 0,
            'transaction_id' => $transactionId,
        ]);
    }

    private function ensureRegistrationTransaction(int $vendorId): int
    {
        $existingId = DB::table('transactions')
            ->where('vendor_id', $vendorId)
            ->where('type', 'registration')
            ->where('status', 'success')
            ->orderByDesc('id')
            ->value('id');

        if ($existingId) {
            return (int) $existingId;
        }

        $organizationUnitId = DB::table('organization_units')->orderBy('id')->value('id');

        return (int) DB::table('transactions')->insertGetId([
            'number' => 'TXN-DUMMY-'.$vendorId.'-'.now()->format('YmdHis'),
            'type' => 'registration',
            'method' => 'manual',
            'amount' => 100,
            'claimed' => 0,
            'status' => 'success',
            'fpx_job_status' => 0,
            'organization_unit_id' => $organizationUnitId,
            'vendor_id' => $vendorId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
