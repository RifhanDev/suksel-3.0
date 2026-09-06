<?php

namespace Database\Seeders\Ref;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Asas bagi senarai rujukan ringkas (Jenis Tender, Sumber Peruntukan, dan
 * seumpamanya) yang mengisi dropdown pada borang cipta tender.
 *
 * Versi asal setiap seeder ini menggunakan Model::create() tanpa semakan, jadi
 * menjalankannya dua kali menghasilkan pendua — itu menghalangnya daripada
 * dipanggil dari migration, dan itulah sebabnya jadual-jadual ini kekal kosong
 * di staging sehingga dropdown tidak berisi.
 *
 * Di sini baris dipadankan mengikut `name`: yang tiada disisipkan, yang sudah
 * wujud dibiarkan sepenuhnya (tiada tulisan berlebihan pada `updated_at`).
 *
 * Sengaja TIDAK memadam atau menyahaktifkan baris di luar senarai. Jadual ini
 * dirujuk oleh foreign key dari `tenders` — membuang satu baris akan
 * mengosongkan medan berkenaan pada setiap tender yang merujuknya.
 */
abstract class RefListSeeder extends Seeder
{
    abstract protected function table(): string;

    /**
     * Setiap baris mesti mempunyai 'name'. Lajur tambahan (contohnya `is_ssm`)
     * boleh disertakan dan akan disisipkan apa adanya.
     *
     * @return list<array<string, mixed>>
     */
    abstract protected function rows(): array;

    public function run(): void
    {
        $existing = DB::table($this->table())->pluck('name')->all();

        foreach ($this->rows() as $row) {
            if (in_array($row['name'], $existing, true)) {
                continue;
            }

            DB::table($this->table())->insert($row + [
                'active'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
