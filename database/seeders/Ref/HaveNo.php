<?php

namespace Database\Seeders\Ref;

use App\Models\Ref\RefHaveNo;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class HaveNo extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Create Admin User
        RefHaveNo::create([
            'name' => 'Ada',
            'active' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        RefHaveNo::create([
            'name' => 'Tiada',
            'active' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
