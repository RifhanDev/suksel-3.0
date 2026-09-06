<?php

use Database\Seeders\Ref\OpenTo;
use Database\Seeders\Ref\OrganizationTypes;
use Database\Seeders\Ref\SumberPeruntukan;
use Database\Seeders\Ref\TypeOfPemenuhan;
use Database\Seeders\Ref\TypeOfTender;
use Database\Seeders\Ref\YesNo;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Mengisi baki senarai rujukan yang menyokong borang cipta tender.
 *
 * Jadual-jadual ini kosong di staging, jadi dropdown yang bergantung padanya
 * tidak berisi — Jenis Tender / Sebut Harga ialah yang pertama disedari, tetapi
 * kesemuanya terjejas. Seedernya sentiasa wujud; tiada apa yang pernah
 * memanggilnya semasa deploy, dan ia menggunakan create() tanpa semakan
 * sehingga tidak selamat dipanggil dari migration.
 *
 * Seeder itu kini mewarisi RefListSeeder, yang memadankan mengikut nama,
 * menyisipkan yang tiada, dan tidak pernah memadam atau mengubah baris sedia
 * ada — lihat docblocknya. Tiada data didua di sini.
 *
 * Menyusul 2027_08_30_000004, yang melakukan perkara sama untuk
 * ref_type_of_contracts apabila dropdown Jenis Kontrak didapati kosong.
 */
return new class extends Migration
{
    private const SEEDERS = [
        'ref_type_of_tenders'     => TypeOfTender::class,
        'ref_sumber_peruntukans'  => SumberPeruntukan::class,
        'ref_yes_nos'             => YesNo::class,
        'ref_open_tos'            => OpenTo::class,
        'ref_type_of_pemenuhans'  => TypeOfPemenuhan::class,
        'ref_organization_types'  => OrganizationTypes::class,
    ];

    public function up(): void
    {
        foreach (self::SEEDERS as $table => $seeder) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            (new $seeder())->run();
        }
    }

    public function down(): void
    {
        // Sengaja tidak dilaksanakan. Setiap jadual ini dirujuk oleh foreign key
        // dari `tenders` atau `vendors`, jadi membuang baris akan mengosongkan
        // medan pada rekod sedia ada. Baris tambahan tidak merosakkan apa-apa.
    }
};
