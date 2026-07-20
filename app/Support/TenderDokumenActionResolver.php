<?php

namespace App\Support;

class TenderDokumenActionResolver
{
    public static function resolve(?string $sourceType, ?string $mechanism, ?string $vendorAction): string
    {
        $sourceType = strtolower(trim((string) $sourceType));
        $mechanism = strtolower(trim((string) $mechanism));
        $vendorAction = strtolower(trim((string) $vendorAction));

        if (in_array($sourceType, ['specification_document', 'specification'], true)) {
            return 'view_specification';
        }

        if ($sourceType === 'borang_atas_talian' || $mechanism === 'borang_atas_talian') {
            return 'online_form';
        }

        if ($mechanism === 'ptj_muat_naik') {
            return $vendorAction === 'muat_turun' ? 'download_only' : 'download_upload';
        }

        if ($vendorAction === 'kunci_masuk') {
            return 'key_in';
        }

        return 'vendor_upload';
    }
}
