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
        $kategori1And2Options = 
        [
            'ICT',
            'Bekalan',
            'Perkhidmatan',
            'Kerja',
            'Perunding',
            'Sewaan'
        ];

        foreach ([1, 2] as $kategoriId) 
        {
            foreach ($kategori1And2Options as $name)
            {
                RefTypeOfPerolehan::create(
                [
                    'ref_kategori_jenis_perolehan_id' => $kategoriId,
                    'name' => $name,
                    'active' => true,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        $kategoriOthersOptions = 
        [
            'Bangunan',
            'Kejuruteraan Awam',
            'M&E',
            'Landskap',
            'Kerja Khas'
        ];

        foreach ($kategoriOthersOptions as $name)
        {
            RefTypeOfPerolehan::create(
            [
                'ref_kategori_jenis_perolehan_id' => 3,
                'name' => $name,
                'active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
