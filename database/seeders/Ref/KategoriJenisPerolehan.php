<?php

namespace Database\Seeders\Ref;

use App\Models\Ref\RefKategoriJenisPerolehan;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class KategoriJenisPerolehan extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Create Admin User
        RefKategoriJenisPerolehan::create([
            'name' => 'Perkhidmatan',
            'active' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        RefKategoriJenisPerolehan::create([
            'name' => 'Bekalan',
            'active' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        RefKategoriJenisPerolehan::create([
            'name' => 'Kerja',
            'active' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
