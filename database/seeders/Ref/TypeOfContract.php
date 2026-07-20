<?php

namespace Database\Seeders\Ref;

use App\Models\Ref\RefTypeOfContract;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TypeOfContract extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Create Admin User
        RefTypeOfContract::create([
            'name' => 'Kementerian',
            'active' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        RefTypeOfContract::create([
            'name' => 'Bukan Kementerian',
            'active' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
