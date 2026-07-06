<?php

namespace App\Services;

use App\Models\PenyediaanIklan;
use App\Support\TenderProcessStatus;
use App\Tender;
use App\User;
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

        $meta['pegawai'] = $this->normalizePegawaiMeta($meta['pegawai'] ?? []);

        return [
            'meta' => $meta,
            'penyediaan_iklan' => $penyediaanIklan,
        ];
    }

    public function normalizePegawaiMeta(array $pegawai): array
    {
        $pegawai1 = $pegawai['pegawai1'] ?? [];
        $pegawai2 = $pegawai['pegawai2'] ?? [];

        $pegawai2 = $this->resolvePegawai2Record($pegawai2);

        return [
            'pegawai1' => [
                'nama' => trim((string) ($pegawai1['nama'] ?? '')),
                'emel' => trim((string) ($pegawai1['emel'] ?? '')),
                'tel' => trim((string) ($pegawai1['tel'] ?? '')),
                'jabatan' => trim((string) ($pegawai1['jabatan'] ?? '')),
            ],
            'pegawai2' => $pegawai2,
        ];
    }

    protected function resolvePegawai2Record(array $pegawai2): array
    {
        $userId = $pegawai2['user_id'] ?? null;
        if ($userId) {
            $user = User::query()->find($userId);
            if ($user) {
                return [
                    'user_id' => (int) $user->id,
                    'nama' => $user->name,
                    'emel' => trim((string) ($pegawai2['emel'] ?? $user->email ?? '')),
                    'tel' => trim((string) ($pegawai2['tel'] ?? $user->tel ?? '')),
                    'jabatan' => trim((string) ($pegawai2['jabatan'] ?? $user->department ?? '')),
                ];
            }
        }

        $nama = trim((string) ($pegawai2['nama'] ?? ''));
        if ($nama !== '' && ctype_digit($nama)) {
            $user = User::query()->find((int) $nama);
            if ($user) {
                return [
                    'user_id' => (int) $user->id,
                    'nama' => $user->name,
                    'emel' => trim((string) ($pegawai2['emel'] ?? $user->email ?? '')),
                    'tel' => trim((string) ($pegawai2['tel'] ?? $user->tel ?? '')),
                    'jabatan' => trim((string) ($pegawai2['jabatan'] ?? $user->department ?? '')),
                ];
            }
        }

        return [
            'user_id' => null,
            'nama' => $nama,
            'emel' => trim((string) ($pegawai2['emel'] ?? '')),
            'tel' => trim((string) ($pegawai2['tel'] ?? '')),
            'jabatan' => trim((string) ($pegawai2['jabatan'] ?? '')),
        ];
    }

    public function save(Tender $tender, array $payload, bool $submit = false): PenyediaanIklan
    {
        return DB::transaction(function () use ($tender, $payload, $submit) {
            $existing = PenyediaanIklan::query()->where('tender_id', $tender->id)->first();
            $previousTaklimat = is_array($existing?->meta)
                ? ($existing->meta['iklan']['taklimat'] ?? [])
                : [];

            $taklimat = $payload['iklan']['taklimat'] ?? [];
            $payload['iklan']['taklimat'] = $this->syncTaklimatToSiteVisits(
                $tender,
                $taklimat,
                $previousTaklimat
            );

            if (count($payload['iklan']['taklimat']) > 0) {
                Tender::query()->where('id', $tender->id)->update(['lawatan_tapak' => 1]);
            }

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

    /**
     * Mirror Penyediaan Iklan taklimat / lawatan tapak rows into tender_visits
     * so vendor tender pages can display them (they read siteVisits, not meta).
     */
    protected function syncTaklimatToSiteVisits(Tender $tender, array $taklimatRows, array $previousTaklimat): array
    {
        $previousIds = collect($previousTaklimat)
            ->pluck('visit_id')
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();

        if ($taklimatRows === []) {
            if ($previousIds !== []) {
                $tender->siteVisits()->whereIn('id', $previousIds)->delete();
            }

            return [];
        }

        $keptIds = [];
        $updatedRows = [];

        foreach ($taklimatRows as $row) {
            $datetime = $this->parseTaklimatDatetime($row['tarikh'] ?? null, $row['masa'] ?? null);
            if ($datetime === null) {
                continue;
            }

            $attrs = [
                'meetpoint' => trim((string) ($row['perihal'] ?? '')) ?: '-',
                'address' => trim((string) ($row['lokasi'] ?? '')) ?: '-',
                'datetime' => $datetime,
                'required' => ($row['kehadiran'] ?? '') === 'Wajib',
            ];

            $visitId = $row['visit_id'] ?? null;
            $visit = $visitId ? $tender->siteVisits()->find($visitId) : null;

            if ($visit) {
                $visit->update($attrs);
            } else {
                $visit = $tender->siteVisits()->create(array_merge($attrs, [
                    'tender_id' => $tender->id,
                ]));
            }

            $keptIds[] = (int) $visit->id;
            $updatedRows[] = array_merge($row, ['visit_id' => (int) $visit->id]);
        }

        $toDelete = array_diff($previousIds, $keptIds);
        if ($toDelete !== []) {
            $tender->siteVisits()->whereIn('id', $toDelete)->delete();
        }

        return $updatedRows;
    }

    protected function parseTaklimatDatetime(?string $date, ?string $time): ?string
    {
        $parsedDate = $this->parseDate($date);
        if ($parsedDate === null) {
            return null;
        }

        $parsedTime = trim((string) $time);
        if ($parsedTime === '') {
            $parsedTime = '00:00';
        }

        try {
            return Carbon::parse($parsedDate . ' ' . $parsedTime)->format('Y-m-d H:i:s');
        } catch (\Throwable) {
            try {
                return Carbon::parse($parsedDate)->startOfDay()->format('Y-m-d H:i:s');
            } catch (\Throwable) {
                return null;
            }
        }
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

        $updates['status_process_id'] = TenderProcessStatus::PENYEDIAAN_IKLAN;

        Tender::query()->where('id', $tender->id)->update($updates);
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
