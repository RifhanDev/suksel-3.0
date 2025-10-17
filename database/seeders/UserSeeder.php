<?php

namespace Database\Seeders;

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
        $now = date('Y-m-d H:i:s');

        $exists = DB::table('users')->where('email', 'test.user@example.com')->exists();
        if (! $exists) {
            DB::table('users')->insert([
                'name' => 'Test User',
                'username' => 'testuser',
                'email' => 'test.user@example.com',
                'password' => Hash::make('Password1!'),
                'confirmed' => 1,
                'approved' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
