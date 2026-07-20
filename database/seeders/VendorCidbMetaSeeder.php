<?php

namespace Database\Seeders;

use App\Support\VendorCidbMeta;
use App\Vendor;
use Illuminate\Database\Seeder;

class VendorCidbMetaSeeder extends Seeder
{
    public function run(): void
    {
        Vendor::query()
            ->whereNull('meta')
            ->orderBy('id')
            ->limit(5)
            ->get()
            ->each(function (Vendor $vendor) {
                $sections = VendorCidbMeta::dummySections();

                $sections[VendorCidbMeta::SECTION_MAKLUMAT_PENDAFTARAN_CIDB]['no_pendaftaran'] =
                    $vendor->cidb_ref_no ?: $sections[VendorCidbMeta::SECTION_MAKLUMAT_PENDAFTARAN_CIDB]['no_pendaftaran'];

                $sections[VendorCidbMeta::SECTION_MAKLUMAT_SYARIKAT]['nama_syarikat'] =
                    $vendor->name ?: $sections[VendorCidbMeta::SECTION_MAKLUMAT_SYARIKAT]['nama_syarikat'];

                $sections[VendorCidbMeta::SECTION_MAKLUMAT_SYARIKAT]['no_pendaftaran_ssm'] =
                    $vendor->registration ?: $sections[VendorCidbMeta::SECTION_MAKLUMAT_SYARIKAT]['no_pendaftaran_ssm'];

                $sections[VendorCidbMeta::SECTION_MAKLUMAT_SYARIKAT]['alamat_berdaftar'] =
                    $vendor->address ?: $sections[VendorCidbMeta::SECTION_MAKLUMAT_SYARIKAT]['alamat_berdaftar'];

                $sections[VendorCidbMeta::SECTION_KOMPOSISI_BUMIPUTERA]['peratus_bumiputera'] =
                    (float) ($vendor->bumi_percentage ?? $sections[VendorCidbMeta::SECTION_KOMPOSISI_BUMIPUTERA]['peratus_bumiputera']);

                $sections[VendorCidbMeta::SECTION_KOMPOSISI_BUMIPUTERA]['peratus_bukan_bumiputera'] =
                    (float) ($vendor->nonbumi_percentage ?? $sections[VendorCidbMeta::SECTION_KOMPOSISI_BUMIPUTERA]['peratus_bukan_bumiputera']);

                $sections[VendorCidbMeta::SECTION_KOMPOSISI_BUMIPUTERA]['peratus_asing'] =
                    (float) ($vendor->foreigner_percentage ?? $sections[VendorCidbMeta::SECTION_KOMPOSISI_BUMIPUTERA]['peratus_asing']);

                $syncedAt = now()->toIso8601String();

                $vendor->meta = [
                    'source' => 'cidb',
                    'synced_at' => $syncedAt,
                    'synced_by' => null,
                    'source1' => VendorCidbMeta::buildSnapshot(VendorCidbMeta::emptySections(), 'Sebelum', null),
                    'source2' => VendorCidbMeta::buildSnapshot($sections, 'Selepas', $syncedAt),
                    'diff' => [
                        'has_changes' => false,
                        'change_count' => 0,
                        'rows' => [],
                    ],
                ];

                $vendor->save();
            });
    }
}
