<?php

namespace Database\Seeders\Ref;

use App\Models\Ref\RefTypeOfPemenuhan;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TypeOfPemenuhan extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Create Admin User
        RefTypeOfPemenuhan::create([
            'name' => 'Bermasa (Bila Perlu)',
            'active' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        RefTypeOfPemenuhan::create([
            'name' => 'Sepenuh Masa',
            'active' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
