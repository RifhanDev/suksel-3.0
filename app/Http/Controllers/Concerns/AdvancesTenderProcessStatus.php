<?php

namespace App\Http\Controllers\Concerns;

use App\Services\TenderProcessStatusService;
use App\Tender;

trait AdvancesTenderProcessStatus
{
    protected function advanceTenderProcess(Tender $tender, int $targetStatus, ?int $requiredStatus = null): bool
    {
        if ($requiredStatus !== null && (int) ($tender->status_process_id ?? 0) !== $requiredStatus) {
            return false;
        }

        app(TenderProcessStatusService::class)->advanceStatus($tender, $targetStatus);

        return true;
    }
}
