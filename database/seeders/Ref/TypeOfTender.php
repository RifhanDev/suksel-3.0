<?php

namespace Database\Seeders\Ref;

use App\Models\Ref\RefTypeOfTender;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TypeOfTender extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Create Admin User
        RefTypeOfTender::create([
            'name' => 'Konvensional',
            'active' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        RefTypeOfTender::create([
            'name' => 'Reka & Bina',
            'active' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        RefTypeOfTender::create([
            'name' => 'Terhad',
            'active' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
