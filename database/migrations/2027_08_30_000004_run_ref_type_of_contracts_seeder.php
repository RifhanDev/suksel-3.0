<?php

use Database\Seeders\Ref\TypeOfContract;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Mengisi `ref_type_of_contracts` semasa deploy, supaya dropdown "Jenis Kontrak"
 * pada borang cipta tender tidak kosong.
 *
 * Jadual ini berisi pada pangkalan data yang disalin daripada produksi, tetapi
 * kosong pada pemasangan baharu dan pangkalan data pembangunan — tiada migration
 * pernah menyemainya. Dropdown itu dahulunya menyembunyikan jurang ini dengan
 * menyenaraikan pilihan berkod tegar (1/2/3), yang tidak sepadan dengan baris
 * sebenar dan menyebabkan penyimpanan gagal dengan ralat FK 1452. Selepas
 * dropdown itu dibetulkan supaya membaca jadual ini, jadual yang kosong bermakna
 * dropdown yang kosong — jadi ia mesti diisi.
 *
 * Tiada data didua di sini; senarai dan sebab ia selamat dijalankan berulang
 * kali ada dalam docblock seeder.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ref_type_of_contracts')) {
            return;
        }

        (new TypeOfContract())->run();
    }

    public function down(): void
    {
        if (! Schema::hasTable('ref_type_of_contracts')) {
            return;
        }

        $query = DB::table('ref_type_of_contracts')->whereIn('name', TypeOfContract::CONTRACTS);

        // Jangan buang baris yang masih dirujuk. FK menggunakan onDelete('set null'),
        // jadi memadamnya akan mengosongkan jenis kontrak pada tender sedia ada —
        // rollback tidak sepatutnya merosakkan data tender.
        if (Schema::hasColumn('tenders', 'jenis_kontrak_id')) {
            $query->whereNotIn('id', function ($sub) {
                $sub->select('jenis_kontrak_id')
                    ->from('tenders')
                    ->whereNotNull('jenis_kontrak_id');
            });
        }

        $query->delete();
    }
};
