<?php

namespace App\Http\Controllers\Concerns;

use App\Tender;
use Carbon\Carbon;

trait ResolvesTenderForProcess
{
    protected function resolveTenderByIdentifier($identifier): ?Tender
    {
        if (empty($identifier)) {
            return null;
        }

        if (is_numeric($identifier)) {
            return Tender::query()->where('id', (int) $identifier)->first();
        }

        return Tender::query()
            ->where(function ($q) use ($identifier) {
                $q->where('uuid', $identifier)
                  ->orWhere('no_tender', $identifier)
                  ->orWhere('ref_number', $identifier);
            })
            ->first();
    }

    /** @return list<array<string, mixed>> */
    protected function mapTendersForProcessList(iterable $tenders, callable $showUrl): array
    {
        return collect($tenders)->map(function (Tender $tender) use ($showUrl) {
            $submissionDate = null;
            if (! empty($tender->submission_datetime)) {
                $submissionDate = Carbon::parse($tender->submission_datetime);
            }

            $noTender = $tender->no_tender ?: $tender->ref_number ?: (string) $tender->id;

            return [
                'id' => $tender->id,
                'uuid' => $tender->uuid,
                'no_tender' => $noTender,
                'tajuk' => $tender->name ?: '-',
                'tarikh' => $submissionDate ? $submissionDate->format('d/m/Y') : '-',
                'status_label' => 'Dalam Proses',
                'show_url' => $showUrl($tender, $noTender),
            ];
        })->values()->all();
    }
}
