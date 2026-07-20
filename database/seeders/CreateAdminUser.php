<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\User;
use App\Role;
use App\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CreateAdminUser extends Seeder
{
    public function run()
    {
        // Create or get Admin role
        $adminRole = Role::firstOrCreate(
            ['name' => 'Admin'],
            [
                'display_name' => 'Administrator',
                'description' => 'System Administrator with full access'
            ]
        );

        // Create admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@suksel.com'],
            [
                'username' => 'admin',
                'name' => 'Tender Admin',
                'password' => Hash::make('admin123'),
                'confirmed' => 1,
                'approved' => 1,
            ]
        );

        // Attach role to user using role_user table
        DB::table('role_user')->updateOrInsert(
            [
                'user_id' => $admin->id,
                'role_id' => $adminRole->id
            ]
        );

        // Also assign using Spatie if available
        try {
            if (method_exists($admin, 'assignRole')) {
                $admin->assignRole('Admin');
            }
        } catch (\Exception $e) {
            // Spatie not fully configured, that's ok
        }

        $this->command->info('✅ Admin user created successfully!');
        $this->command->info('📧 Email: admin@suksel.com');
        $this->command->info('🔑 Password: admin123');
        $this->command->info('👤 Name: Tender Admin');
        $this->command->info('🎯 Role: Admin');
    }
}
