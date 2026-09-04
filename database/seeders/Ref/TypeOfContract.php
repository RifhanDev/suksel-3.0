<?php

namespace Database\Seeders\Ref;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Senarai rujukan "Jenis Kontrak" pada borang cipta tender.
 *
 * Selamat dijalankan berulang kali, termasuk di produksi: baris dipadankan
 * mengikut `name`, yang tiada disisipkan, dan yang sudah wujud dibiarkan
 * sepenuhnya. Versi asal menggunakan create() tanpa semakan, jadi menjalankannya
 * dua kali menghasilkan pendua — itu menghalangnya daripada dipanggil dari
 * migration.
 *
 * Sengaja TIDAK memadam atau menyahaktifkan baris di luar senarai ini.
 * `tenders.jenis_kontrak_id` mempunyai foreign key ke jadual ini dengan
 * onDelete('set null'), jadi membuang satu baris akan mengosongkan jenis kontrak
 * pada setiap tender yang merujuknya.
 */
class TypeOfContract extends Seeder
{
    public const CONTRACTS = [
        'Kementerian',
        'Bukan Kementerian',
    ];

    public function run(): void
    {
        $existing = DB::table('ref_type_of_contracts')->pluck('name')->all();

        foreach (self::CONTRACTS as $name) {
            if (in_array($name, $existing, true)) {
                continue;
            }

            DB::table('ref_type_of_contracts')->insert([
                'name'       => $name,
                'active'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
