<?php

namespace App\Support;

class VendorActionLabel
{
    public static function resolve(?string $sourceType, ?string $mechanism, ?string $vendorAction): string
    {
        $sourceType = strtolower(trim((string) $sourceType));
        $mechanism = strtolower(trim((string) $mechanism));

        if (in_array($sourceType, ['specification_document', 'specification'], true)) {
            return 'Spesifikasi';
        }

        if ($sourceType === 'borang_atas_talian' || $mechanism === 'borang_atas_talian') {
            return 'Borang Atas Talian';
        }

        if ($mechanism === 'ptj_muat_naik') {
            return match ($vendorAction) {
                'muat_turun' => 'Muat Turun',
                'muat_turun_naik' => 'Muat Turun dan Muat Naik',
                default => 'Muat Turun dan Muat Naik',
            };
        }

        return 'Muat Naik';
    }

    public static function badgeClass(string $label): string
    {
        return match ($label) {
            'Spesifikasi' => 'badge-status-info',
            'Borang Atas Talian' => 'badge-status-warning',
            'Muat Naik' => 'badge-status-success',
            'Muat Turun', 'Muat Turun dan Muat Naik' => 'badge-status-neutral',
            default => 'badge-status-neutral',
        };
    }
}
