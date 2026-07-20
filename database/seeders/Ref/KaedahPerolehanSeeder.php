<?php

namespace Database\Seeders\Ref;

use App\Models\Ref\RefKaedahPerolehan;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class KaedahPerolehanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Create Admin User
        RefKaedahPerolehan::create([
            'name' => 'Tender',
            'active' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        RefKaedahPerolehan::create([
            'name' => 'Sebut Harga',
            'active' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
