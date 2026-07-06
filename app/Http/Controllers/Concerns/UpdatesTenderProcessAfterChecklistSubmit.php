<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Tender;
use App\Services\TenderProcessStatusService;

trait UpdatesTenderProcessAfterChecklistSubmit
{
    protected function refreshTenderProcessAfterChecklistSubmit(string $tenderUuid): void
    {
        $tender = Tender::query()->where('uuid', $tenderUuid)->first();

        if (! $tender) {
            return;
        }

        app(TenderProcessStatusService::class)->syncAfterChecklistSubmit($tender);
    }
}
