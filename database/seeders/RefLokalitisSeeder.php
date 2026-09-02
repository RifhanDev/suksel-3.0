<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
