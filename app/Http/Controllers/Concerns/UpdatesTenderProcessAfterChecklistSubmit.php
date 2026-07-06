<?php

namespace App\Http\Controllers\Concerns;

use App\Services\TenderProcessStatusService;
use App\Tender;

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
