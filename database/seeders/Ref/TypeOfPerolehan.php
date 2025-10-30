<?php

namespace Database\Seeders\Ref;

use App\Models\Ref\RefTypeOfPerolehan;
use App\Models\Ref\RefTypeOfTender;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TypeOfPerolehan extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Create Admin User
        RefTypeOfPerolehan::create([
            'name' => 'ICT',
            'active' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        RefTypeOfPerolehan::create([
            'name' => 'Lain-lain',
            'active' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
