<?php

namespace Database\Seeders\Ref;

use App\Models\Ref\RefYesNo;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class YesNo extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Create Admin User
        RefYesNo::create([
            'name' => 'Ya',
            'active' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        RefYesNo::create([
            'name' => 'Tidak',
            'active' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
