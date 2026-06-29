<?php

namespace App\Support;

use App\Vendor;
use Carbon\Carbon;

class VendorProfilPetenderDefaults
{
    /**
     * @return list<string>
     */
    public static function lockedFieldKeys(): array
    {
        return [
            'nama_syarikat',
            'jenis_syarikat',
            'taraf_petender',
            'alamat',
            'pegawai_nama',
            'pegawai_telefon',
            'pegawai_emel',
            'no_ssm',
            'no_mof',
            'tempoh_sah_mof',
            'modal_berbayar',
            'modal_dibenarkan',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function fromVendor(?Vendor $vendor): array
    {
        if (! $vendor) {
            return [];
        }

        $jenisSyarikat = self::mapJenisSyarikat($vendor->organization_type);
        $tarafPetender = self::mapTarafPetender($vendor);

        return [
            'nama_syarikat' => (string) ($vendor->name ?? ''),
            'jenis_syarikat' => $jenisSyarikat,
            'jenis_syarikat_label' => self::jenisSyarikatLabel($vendor->organization_type, $jenisSyarikat),
            'taraf_petender' => $tarafPetender,
            'taraf_petender_label' => self::tarafPetenderLabel($tarafPetender),
            'alamat' => (string) ($vendor->address ?? ''),
            'pegawai_nama' => (string) ($vendor->officer_name ?? ''),
            'pegawai_telefon' => (string) ($vendor->officer_tel ?: $vendor->tel ?? ''),
            'pegawai_emel' => (string) ($vendor->officer_email ?? ''),
            'no_ssm' => (string) ($vendor->registration ?? ''),
            'no_mof' => (string) ($vendor->mof_ref_no ?? ''),
            'tempoh_sah_mof' => self::formatMofPeriod($vendor),
            'modal_berbayar' => (float) ($vendor->paidup_capital ?? 0),
            'modal_dibenarkan' => (float) ($vendor->authorized_capital ?? 0),
        ];
    }

    /**
     * @param  array<string, mixed>  $payload
     * @param  array<string, mixed>  $defaults
     * @return array<string, mixed>
     */
    public static function mergeLockedFields(array $payload, array $defaults): array
    {
        foreach (self::lockedFieldKeys() as $key) {
            if (array_key_exists($key, $defaults)) {
                $payload[$key] = $defaults[$key];
            }
        }

        return $payload;
    }

    public static function mapJenisSyarikat(?string $organizationType): string
    {
        if (! $organizationType) {
            return '';
        }

        $upper = strtoupper($organizationType);

        if (str_contains($upper, 'PERSEORANGAN') || str_contains($upper, 'PERTUBUHAN') || str_contains($upper, 'PERSATUAN')) {
            return 'persendirian';
        }

        if (str_contains($upper, 'PERKONGSIAN')) {
            return 'perkongsian';
        }

        if (str_contains($upper, 'KOPERASI')) {
            return 'koperasi';
        }

        if (str_contains($upper, 'BERHAD') || str_contains($upper, 'SDN')) {
            return 'sdn_bhd';
        }

        return '';
    }

    public static function jenisSyarikatLabel(?string $organizationType, ?string $mappedValue = null): string
    {
        if ($organizationType && isset(Vendor::$organizationTypes[$organizationType])) {
            return Vendor::$organizationTypes[$organizationType];
        }

        if ($organizationType) {
            return $organizationType;
        }

        return match ($mappedValue) {
            'persendirian' => 'Persendirian',
            'perkongsian' => 'Perkongsian',
            'koperasi' => 'Koperasi',
            'sdn_bhd' => 'Sdn Bhd',
            default => '',
        };
    }

    public static function mapTarafPetender(Vendor $vendor): string
    {
        if ($vendor->mof_bumi || $vendor->cidb_bumi) {
            return 'bumiputera';
        }

        if ((float) $vendor->bumi_percentage >= 50) {
            return 'bumiputera';
        }

        return 'bukan_bumiputera';
    }

    public static function tarafPetenderLabel(?string $value): string
    {
        return match ($value) {
            'bumiputera' => 'Bumiputera',
            'bukan_bumiputera' => 'Bukan Bumiputera',
            default => '',
        };
    }

    public static function formatMofPeriod(Vendor $vendor): string
    {
        if (empty($vendor->mof_start_date) || empty($vendor->mof_end_date)) {
            return '';
        }

        try {
            $start = Carbon::parse($vendor->mof_start_date)->format('d/m/Y');
            $end = Carbon::parse($vendor->mof_end_date)->format('d/m/Y');

            return "{$start} - {$end}";
        } catch (\Throwable) {
            return trim(($vendor->mof_start_date ?? '') . ' - ' . ($vendor->mof_end_date ?? ''));
        }
    }
}
