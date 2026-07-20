<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ref\RefLokaliti;

class RefLokalitisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lokalitis = [
            [
                'name' => 'Daerah Terpilih',
                'description' => 'Lokaliti liputan untuk daerah yang dipilih',
                'active' => true,
            ],
            [
                'name' => 'Zon Terpilih',
                'description' => 'Lokaliti liputan untuk zon yang dipilih',
                'active' => true,
            ],
        ];

        foreach ($lokalitis as $lokaliti) {
            RefLokaliti::create($lokaliti);
        }
    }
}
