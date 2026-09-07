<?php

namespace Database\Seeders;

use App\Role;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Seeds officer users from Book2.xlsx (Nama / Telefon / E-mel).
 *
 * Run:
 *   php artisan db:seed --class=DummyUsersSeeder
 *
 * Password for all users: Test@1234
 * If email already exists: set confirmed + approved, and reset password.
 * Ensures login-ready flags: role, arr, password_changed_at, organization_unit_id.
 */
class DummyUsersSeeder extends Seeder
{
    public const PASSWORD = 'Test@1234';

    public const DEFAULT_ROLE = 'Admin';

    /**
     * @var list<array{name: string, tel: ?string, email: string}>
     */
    private array $users = [
        ['name' => 'ADZWA NISYHA IZLIN BINTI ZAINUDDIN', 'tel' => '176640546', 'email' => 'adzwa@padat.gov.my'],
        ['name' => 'AIDIL IFNI BIN SUPAR', 'tel' => '172362776', 'email' => 'aidil.ifni@mbsj.gov.my'],
        ['name' => 'AMIN BIN ALIAS', 'tel' => '1121228358', 'email' => 'amin@mbsa.gov.my'],
        ['name' => 'AMIRUL SYAFIQ BIN ABDUL SAMAD', 'tel' => '126559547', 'email' => 'amirul.syafiq@selangor.gov.my'],
        ['name' => 'FAIZ IEDZLAN BIN RIDZUAN', 'tel' => '1160531653', 'email' => 'faiz.iedzlan@mbdk.gov.my'],
        ['name' => 'MOHAMAD AZIM BIN ABDUL HALIM', 'tel' => '123635056', 'email' => 'azim@mbsq.gov.my'],
        ['name' => 'MOHAMMAD HAFIZ BIN ABDUL SHUKOR', 'tel' => '1123247875', 'email' => 'mhafiz@selangor.gov.my'],
        ['name' => 'MOHAMMAD NABIL BIN HAJI ABU NAIM', 'tel' => '133886030', 'email' => 'nabil@padat.gov.my'],
        ['name' => 'MOHD HANIF BIN HAMDAN', 'tel' => '142628557', 'email' => 'hanifhamdan@selangor.gov.my'],
        ['name' => 'MOHD KHOIR BIN SHAFIE', 'tel' => '123647977', 'email' => 'khoir@waterselangor.gov.my'],
        ['name' => 'MUHAMAD FAIZ BIN ABDUL RAHMAN', 'tel' => '019-604 0816', 'email' => 'm.faiz@selangor.gov.my'],
        ['name' => 'MUHAMAD HILMI BIN ROJI', 'tel' => '019-381 5033', 'email' => 'hilmir@selangor.gov.my'],
        ['name' => 'MUHAMMAD HAFIZ BIN RAMLI', 'tel' => null, 'email' => 'hafiz@selangor.gov.my'],
        ['name' => 'MUHAMMAD HAFIZ HAFIZI BIN MUDA', 'tel' => '017-2764614', 'email' => 'h.hafizi@jkr.gov.my'],
        ['name' => 'MUHAMMAD HAMMAWI BIN ABD HAMID', 'tel' => '123802540', 'email' => 'hammawi@waterselangor.gov.my'],
        ['name' => 'MUHAMMAD NASIR BIN HAMIR', 'tel' => '1115659184', 'email' => 'mnasir@selangor.gov.my'],
        ['name' => 'NIZAR MUIZZ BIN ZAHARI', 'tel' => '194190488', 'email' => 'muizz@waterselangor.gov.my'],
        ['name' => 'NOOR AQILAH BINTI ABDUL HALIM', 'tel' => '17630490', 'email' => 'nooraqilah@waterselangor.gov.my'],
        ['name' => 'NOORZAIHA BINTI HARUN', 'tel' => '193189207', 'email' => 'noorzaiha@selangor.gov.my'],
        ['name' => 'NOR SAZATUL HUSNA BINTI MOHAMAD NOR KOSIM', 'tel' => '019-8124249', 'email' => 'norsazatul@jkr.gov.my'],
        ['name' => 'NORHAYATI BINTI SARIF', 'tel' => '013-3975400', 'email' => 'norhayati.sarif@jkr.gov.my'],
        ['name' => 'NUR SYAHIRAH BINTI SARWAN', 'tel' => '167204260', 'email' => 'nursyahirah.sarwan@selangor.gov.my'],
        ['name' => 'NURUL AQILAH BINTI MAT ZIN', 'tel' => '1128874749', 'email' => 'aqilah@waterselangor.gov.my'],
        ['name' => "QURRATU 'AINI BINTI ZAKARIA", 'tel' => '166306562', 'email' => 'q.aini@jkr.gov.my'],
        ['name' => 'REDZUAN MOHD JAAFRI', 'tel' => '126923919', 'email' => 'mohdredzuan.mohdjaafri@mbpj.gov.my'],
        ['name' => 'SALIZA BT MOHD MASRI', 'tel' => '192639034', 'email' => 'saliza@mbpj.gov.my'],
        ['name' => 'SITI ZUBAIDAH BINTI BAHARI', 'tel' => '+60 19-356 2565', 'email' => 'sitizubaidahb@jkr.gov.my'],
        ['name' => 'ZAINUL ULUM BIN MOHAMAD TAMZIS', 'tel' => '105408789', 'email' => 'zainul.tamzis@mbdk.gov.my'],
    ];

    public function run(): void
    {
        $password = Hash::make(self::PASSWORD);
        $now = now();
        $created = 0;
        $updated = 0;

        $role = Role::query()->where('name', self::DEFAULT_ROLE)->first();
        if (! $role) {
            $this->command?->error('Role "' . self::DEFAULT_ROLE . '" not found. Run RoleSeeder first.');

            return;
        }

        $organizationUnitId = DB::table('organization_units')->orderBy('id')->value('id');

        foreach ($this->users as $row) {
            $email = Str::lower(trim($row['email']));
            $name = trim($row['name']);
            $tel = $this->normalizePhone($row['tel'] ?? null);

            $attributes = [
                'name' => $name,
                'password' => $password,
                'confirmed' => 1,
                'approved' => 1,
                'arr' => 1,
                'password_changed_at' => $now,
                'password_reset' => '1',
            ];

            if ($organizationUnitId) {
                $attributes['organization_unit_id'] = $organizationUnitId;
            }

            $user = User::query()->where('email', $email)->first();

            if ($user) {
                if ($tel) {
                    $attributes['tel'] = $tel;
                }

                User::withoutEvents(function () use ($user, $attributes) {
                    $user->forceFill($attributes)->save();
                });

                $this->ensureRole($user, $role);
                $updated++;
                $this->command?->info("Updated (login-ready): {$email}");
                continue;
            }

            $username = $this->uniqueUsername($email);
            $attributes['username'] = $username;
            $attributes['email'] = $email;
            $attributes['tel'] = $tel;
            $attributes['confirmation_code'] = Str::random(32);

            $user = User::withoutEvents(function () use ($attributes) {
                $user = new User();
                $user->forceFill($attributes)->save();

                return $user;
            });

            $this->ensureRole($user, $role);
            $created++;
            $this->command?->info("Created (login-ready): {$email} (username: {$username})");
        }

        $this->command?->newLine();
        $this->command?->info("DummyUsersSeeder done. Created: {$created}, Updated: {$updated}");
        $this->command?->info('Password for all: ' . self::PASSWORD);
        $this->command?->info('Role: ' . self::DEFAULT_ROLE);
        $this->command?->warn('Skipped Excel rows with no e-mel.');
    }

    private function ensureRole(User $user, Role $role): void
    {
        // Keep only the intended role for these seeded login accounts.
        DB::table('role_user')->where('user_id', $user->id)->delete();

        DB::table('role_user')->insert([
            'user_id' => $user->id,
            'role_id' => $role->id,
        ]);
    }

    private function normalizePhone(?string $tel): ?string
    {
        if ($tel === null || trim($tel) === '') {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $tel);
        if ($digits === null || $digits === '') {
            return null;
        }

        if (Str::startsWith($digits, '60') && strlen($digits) >= 10) {
            $digits = '0' . substr($digits, 2);
        }

        if (! Str::startsWith($digits, '0') && strlen($digits) >= 9 && strlen($digits) <= 10) {
            $digits = '0' . $digits;
        }

        return $digits;
    }

    private function uniqueUsername(string $email): string
    {
        $base = Str::lower(Str::before($email, '@'));
        $base = preg_replace('/[^a-z0-9._-]+/', '', $base) ?: 'user';
        $base = Str::limit($base, 100, '');

        $username = $base;
        $i = 1;

        while (User::query()->where('username', $username)->exists()) {
            $username = $base . $i;
            $i++;
        }

        return $username;
    }
}
