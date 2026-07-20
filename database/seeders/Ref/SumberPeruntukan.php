<?php

namespace Database\Seeders\Ref;

use App\Models\Ref\RefSumberPeruntukan;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SumberPeruntukan extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Create Admin User
        RefSumberPeruntukan::create([
            'name' => 'Pembangunan',
            'active' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        RefSumberPeruntukan::create([
            'name' => 'Mengurus',
            'active' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        RefSumberPeruntukan::create([
            'name' => 'Lain-lain',
            'active' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
