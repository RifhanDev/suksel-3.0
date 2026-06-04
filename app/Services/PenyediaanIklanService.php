<?php

namespace App\Services;

use App\Models\PenyediaanIklan;
use App\Tender;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PenyediaanIklanService
{
    public function getForTender(Tender $tender): array
    {
        $meta = ['kelulusan' => PenyediaanIklan::defaultKelulusan()];
        $penyediaanIklan = null;

        $record = PenyediaanIklan::query()->where('tender_id', $tender->id)->first();
        if ($record && is_array($record->meta)) {
            $meta = array_replace_recursive($meta, $record->meta);
            $penyediaanIklan = [
                'tender_id' => $record->tender_id,
                'submitted_at' => $record->submitted_at?->toIso8601String(),
            ];
        }

        if (empty($meta['kelulusan'])) {
            $meta['kelulusan'] = PenyediaanIklan::defaultKelulusan();
        }

        return [
            'meta' => $meta,
            'penyediaan_iklan' => $penyediaanIklan,
        ];
    }

    public function save(Tender $tender, array $payload, bool $submit = false): PenyediaanIklan
    {
        return DB::transaction(function () use ($tender, $payload, $submit) {
            $record = PenyediaanIklan::query()->firstOrNew(['tender_id' => $tender->id]);
            $record->meta = $payload;

            if ($submit) {
                $record->submitted_at = now();
                $this->applyPayloadToTender($tender, $payload);
            }

            $record->save();

            return $record;
        });
    }

    protected function applyPayloadToTender(Tender $tender, array $payload): void
    {
        $iklan = $payload['iklan'] ?? [];
        $syarat = $iklan['syarat'] ?? [];

        $updates = array_filter([
            'advertise_start_date' => $this->parseDate($iklan['tarikh_iklan'] ?? null),
            'advertise_stop_date' => $this->parseDate($iklan['tarikh_tutup'] ?? null),
            'document_start_date' => $this->parseDate($iklan['tarikh_jual'] ?? null),
            'document_stop_date' => $this->parseDate($iklan['tarikh_tutup'] ?? null),
            'submission_datetime' => $this->parseSubmissionDatetime(
                $iklan['tarikh_tutup'] ?? null,
                $iklan['masa_tutup'] ?? null
            ),
            'tender_rules' => $iklan['syarat_tender'] ?? null,
            'only_selangor' => isset($syarat['only_selangor']) ? (int) $syarat['only_selangor'] : null,
            'only_bumiputera' => array_key_exists('only_bumiputera', $syarat) ? (int) (bool) $syarat['only_bumiputera'] : null,
            'invitation' => array_key_exists('invitation', $syarat) ? (int) (bool) $syarat['invitation'] : null,
            'only_advertise' => array_key_exists('only_advertise', $syarat) ? (int) (bool) $syarat['only_advertise'] : null,
        ], fn ($value) => $value !== null && $value !== '');

        if (! empty($syarat['district_list_rule'])) {
            $updates['district_list_rule'] = json_encode($syarat['district_list_rule']);
        }

        if ($updates !== []) {
            Tender::query()->where('id', $tender->id)->update($updates);
        }
    }

    protected function parseDate(?string $value): ?string
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        $formats = ['Y-m-d', 'd/m/Y', 'd-m-Y', 'j M Y', 'd M Y'];
        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, trim($value))->format('Y-m-d');
            } catch (\Throwable) {
                continue;
            }
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }

    protected function parseSubmissionDatetime(?string $date, ?string $time): ?string
    {
        $parsedDate = $this->parseDate($date);
        if ($parsedDate === null) {
            return null;
        }

        $parsedTime = trim((string) $time);
        if ($parsedTime === '') {
            $parsedTime = '12:00';
        }

        try {
            return Carbon::parse($parsedDate . ' ' . $parsedTime)->format('Y-m-d H:i:s');
        } catch (\Throwable) {
            try {
                return Carbon::parse($parsedDate)->setTime(12, 0)->format('Y-m-d H:i:s');
            } catch (\Throwable) {
                return null;
            }
        }
    }
}
