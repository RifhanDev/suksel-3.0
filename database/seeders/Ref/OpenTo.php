<?php

namespace Database\Seeders\Ref;

use App\Models\Ref\RefOpenTo;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OpenTo extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        RefOpenTo::create([
            'name' => 'Bumiputera',
            'active' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        RefOpenTo::create([
            'name' => 'Semua',
            'active' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
