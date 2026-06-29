<?php

namespace App\Support;

use App\Models\Tender as TenderModel;
use App\Tender;

class PenyediaanIklanNavigation
{
    /**
     * Where to send the user after pengurusan-spesifikasi (kewangan) is submitted.
     */
    public static function afterSpecificationUrl(Tender|TenderModel $tender): string
    {
        if ((int) ($tender->status_process_id ?? 0) === 4) {
            return route('penyediaanIklan.show', $tender->id) . '?tab=dokumen';
        }

        return route('pengurusanSpesifikasi');
    }
}
