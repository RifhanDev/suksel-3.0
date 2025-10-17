<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a single login-ready user. Adjust fields if your app requires additional columns.
        $now = date('Y-m-d H:i:s');

        $exists = DB::table('users')->where('email', 'test.user@example.com')->exists();
        if (! $exists) {
            DB::table('users')->insert([
                'name' => 'Test User',
                'username' => 'testuser',
                'email' => 'test.user@example.com',
                // Password meets policy: at least 8 chars, one uppercase, one lowercase, one digit, one special char
                'password' => Hash::make('Password1!'),
                'confirmed' => 1,
                'approved' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
