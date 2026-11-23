<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class VendorProfileTest extends TestCase
{
    use DatabaseTransactions;

    public function test_vendor_profile_handles_a_missing_vendor_relationship(): void
    {
        $user = User::query()->create([
            'name' => 'Vendor Tanpa Syarikat',
            'username' => 'vendor_profile_' . Str::lower(Str::random(10)),
            'email' => 'vendor_profile_' . Str::lower(Str::random(10)) . '@example.test',
            'password' => bcrypt('password'),
            'password_changed_at' => now(),
        ]);

        $role = Role::query()->firstOrCreate(['name' => 'Vendor']);
        $user->assignRole($role);

        $this->actingAs($user)
            ->get(route('profile'))
            ->assertOk()
            ->assertSee('Nama Syarikat')
            ->assertSee('>-</td>', false);
    }
}
