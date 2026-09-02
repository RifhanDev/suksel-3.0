<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Senarai rujukan "Lokaliti Liputan" pada borang cipta tender.
 *
 * Selamat dijalankan berulang kali, termasuk di produksi: baris dipadankan
 * mengikut `name`, yang tiada disisipkan, dan yang sudah betul dibiarkan
 * sepenuhnya (tiada tulisan berlebihan pada `updated_at`).
 *
 * Sengaja TIDAK menyahaktifkan baris di luar senarai ini, berbeza dengan
 * FpxBankListSeeder. Di sana PayNet ialah pemilik senarai, jadi apa-apa di
 * luarnya memang usang. Di sini tiada pihak luar dan tiada halaman
 * pengurusan — RefLokalitisController wujud tetapi tiada route didaftarkan
 * untuknya, jadi satu-satunya cara menambah lokaliti ialah terus ke
 * pangkalan data. Memangkas "yang tiada dalam senarai" akan mematikan
 * tambahan itu secara senyap pada deploy berikutnya dan memecahkan tender
 * yang sudah merujuknya.
 *
 * Atas sebab yang sama, `active` hanya ditetapkan semasa sisipan. Jika
 * seseorang menyahaktifkan satu baris secara sengaja, deploy berikutnya
 * tidak akan menghidupkannya semula.
 */
class RefLokalitisSeeder extends Seeder
{
    public const LOKALITIS = [
        [
            'name'        => 'Daerah Terpilih',
            'description' => 'Lokaliti liputan untuk daerah yang dipilih',
        ],
        [
            'name'        => 'Zon Terpilih',
            'description' => 'Lokaliti liputan untuk zon yang dipilih',
        ],
    ];

    public function run(): void
    {
        $existing = DB::table('ref_lokalitis')->get()->keyBy('name');

        $inserted = 0;
        $updated  = 0;

        foreach (self::LOKALITIS as $lokaliti) {
            $row = $existing->get($lokaliti['name']);

            if (! $row) {
                DB::table('ref_lokalitis')->insert($lokaliti + [
                    'active'     => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $inserted++;

                continue;
            }

            // Hanya keterangan diselaraskan. `active` tidak disentuh — lihat
            // docblock kelas.
            if ($row->description !== $lokaliti['description']) {
                DB::table('ref_lokalitis')
                    ->where('id', $row->id)
                    ->update([
                        'description' => $lokaliti['description'],
                        'updated_at'  => now(),
                    ]);
                $updated++;
            }
        }

        if ($this->command) {
            $this->command->info("RefLokalitisSeeder: {$inserted} disisip, {$updated} dikemas kini.");
        }
    }
}
