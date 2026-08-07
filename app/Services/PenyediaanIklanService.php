<?php

namespace App\Services;

use App\Models\PenyediaanIklan;
use App\Support\TenderProcessStatus;
use App\Tender;
use App\Upload;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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
        } else {
            $meta['kelulusan'] = PenyediaanIklan::normalizeKelulusanRows(
                is_array($meta['kelulusan']) ? $meta['kelulusan'] : []
            );
        }

        $meta['pegawai'] = $this->normalizePegawaiMeta($meta['pegawai'] ?? [], $tender);

        return [
            'meta' => $meta,
            'penyediaan_iklan' => $penyediaanIklan,
        ];
    }

    public function normalizePegawaiMeta(array $pegawai, ?Tender $tender = null): array
    {
        $pegawai1 = $pegawai['pegawai1'] ?? [];
        $pegawai2 = $pegawai['pegawai2'] ?? [];

        $pegawai1 = $this->resolvePegawai1Record($pegawai1, $tender);
        $pegawai2 = $this->resolvePegawai2Record($pegawai2);

        return [
            'pegawai1' => $pegawai1,
            'pegawai2' => $pegawai2,
        ];
    }

    /**
     * Pegawai 1 is always the tender creator; fill blanks from that user.
     *
     * @param  array<string, mixed>  $pegawai1
     * @return array{nama: string, emel: string, tel: string, jabatan: string, user_id: int|null}
     */
    protected function resolvePegawai1Record(array $pegawai1, ?Tender $tender = null): array
    {
        $creator = $tender?->relationLoaded('creator')
            ? $tender->creator
            : ($tender?->creator_id ? User::query()->with('organizationunit')->find($tender->creator_id) : null);

        if ($creator && ! $creator->relationLoaded('organizationunit')) {
            $creator->load('organizationunit');
        }

        $fromCreator = $this->pegawaiFieldsFromUser($creator);

        return [
            'user_id' => $creator?->id,
            'nama' => $this->firstFilled($pegawai1['nama'] ?? null, $fromCreator['nama']),
            'emel' => $this->firstFilled($pegawai1['emel'] ?? null, $fromCreator['emel']),
            'tel' => $this->firstFilled($pegawai1['tel'] ?? null, $fromCreator['tel']),
            'jabatan' => $this->firstFilled($pegawai1['jabatan'] ?? null, $fromCreator['jabatan']),
        ];
    }

    /**
     * @return array{nama: string, emel: string, tel: string, jabatan: string}
     */
    public function pegawaiFieldsFromUser(?User $user): array
    {
        if (! $user) {
            return [
                'nama' => '',
                'emel' => '',
                'tel' => '',
                'jabatan' => '',
            ];
        }

        $jabatan = trim((string) ($user->department ?? ''));
        if ($jabatan === '') {
            $jabatan = trim((string) ($user->jawatan ?? ''));
        }
        if ($jabatan === '' && $user->organizationunit) {
            $jabatan = trim((string) ($user->organizationunit->name ?? ''));
        }

        return [
            'nama' => trim((string) ($user->name ?? '')),
            'emel' => trim((string) ($user->email ?? '')),
            'tel' => trim((string) ($user->tel ?? '')),
            'jabatan' => $jabatan,
        ];
    }

    protected function firstFilled(mixed ...$values): string
    {
        foreach ($values as $value) {
            $trimmed = trim((string) ($value ?? ''));
            if ($trimmed !== '') {
                return $trimmed;
            }
        }

        return '';
    }

    protected function resolvePegawai2Record(array $pegawai2): array
    {
        $userId = $pegawai2['user_id'] ?? null;
        if ($userId) {
            $user = User::query()->with('organizationunit')->find($userId);
            if ($user) {
                $fromUser = $this->pegawaiFieldsFromUser($user);

                return [
                    'user_id' => (int) $user->id,
                    'nama' => $fromUser['nama'],
                    'emel' => $this->firstFilled($pegawai2['emel'] ?? null, $fromUser['emel']),
                    'tel' => $this->firstFilled($pegawai2['tel'] ?? null, $fromUser['tel']),
                    'jabatan' => $this->firstFilled($pegawai2['jabatan'] ?? null, $fromUser['jabatan']),
                ];
            }
        }

        $nama = trim((string) ($pegawai2['nama'] ?? ''));
        if ($nama !== '' && ctype_digit($nama)) {
            $user = User::query()->with('organizationunit')->find((int) $nama);
            if ($user) {
                $fromUser = $this->pegawaiFieldsFromUser($user);

                return [
                    'user_id' => (int) $user->id,
                    'nama' => $fromUser['nama'],
                    'emel' => $this->firstFilled($pegawai2['emel'] ?? null, $fromUser['emel']),
                    'tel' => $this->firstFilled($pegawai2['tel'] ?? null, $fromUser['tel']),
                    'jabatan' => $this->firstFilled($pegawai2['jabatan'] ?? null, $fromUser['jabatan']),
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
            $this->assertTaklimatWithinIklanWindow($payload['iklan'] ?? [], $taklimat);
            $payload['iklan']['taklimat'] = $this->syncTaklimatToSiteVisits(
                $tender,
                $taklimat,
                $previousTaklimat
            );

            if (count($payload['iklan']['taklimat']) > 0) {
                Tender::query()->where('id', $tender->id)->update(['lawatan_tapak' => 1]);
            }

            $previousDocs = is_array($existing?->meta)
                ? ($existing->meta['iklan']['dokumen_sokongan'] ?? [])
                : [];

            if (! empty($payload['_sync_dokumen_meja'])) {
                $payload['iklan']['dokumen_sokongan'] = $this->syncDokumenMejaToTenderUploads(
                    $tender,
                    $payload['iklan']['dokumen_sokongan'] ?? [],
                    $previousDocs
                );
            } else {
                $payload['iklan']['dokumen_sokongan'] = $previousDocs;
            }
            unset($payload['_sync_dokumen_meja']);

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
     * Lawatan/taklimat dates must fall within tarikh iklan … tarikh tutup.
     *
     * @param  array<string, mixed>  $iklan
     * @param  array<int, array<string, mixed>>  $taklimatRows
     */
    protected function assertTaklimatWithinIklanWindow(array $iklan, array $taklimatRows): void
    {
        if ($taklimatRows === []) {
            return;
        }

        $start = $this->parseDate($iklan['tarikh_iklan'] ?? null);
        $end = $this->parseDate($iklan['tarikh_tutup'] ?? null);

        if ($start === null || $end === null) {
            throw ValidationException::withMessages([
                'taklimat' => 'Sila tetapkan Tarikh Iklan dan Tarikh Tutup sebelum menambah lawatan/taklimat.',
            ]);
        }

        $startDay = Carbon::parse($start)->startOfDay();
        $endDay = Carbon::parse($end)->endOfDay();

        foreach ($taklimatRows as $index => $row) {
            $parsed = $this->parseDate($row['tarikh'] ?? null);
            if ($parsed === null) {
                continue;
            }

            $day = Carbon::parse($parsed)->startOfDay();
            if ($day->lt($startDay) || $day->gt($endDay)) {
                $label = trim((string) ($row['perihal'] ?? '')) ?: ('#' . ($index + 1));
                throw ValidationException::withMessages([
                    'taklimat' => "Tarikh lawatan/taklimat \"{$label}\" mesti dalam julat Tarikh Iklan hingga Tarikh Tutup.",
                ]);
            }
        }
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

    public function syncDokumenMejaToTenderUploads(Tender $tender, array $documents, array $previousDocuments = []): array
    {
        $previousByUploadId = collect($previousDocuments)
            ->filter(fn ($doc) => ! empty($doc['upload_id']))
            ->keyBy('upload_id');

        $keptUploadIds = [];
        $updated = [];

        foreach ($documents as $doc) {
            $nama = trim((string) ($doc['nama'] ?? $doc['original_name'] ?? 'Dokumen'));
            if ($nama === '') {
                $nama = $doc['original_name'] ?? 'Dokumen';
            }

            $sourcePath = public_path($doc['path'] ?? '');
            if (! is_file($sourcePath)) {
                continue;
            }

            $uploadId = $doc['upload_id'] ?? null;
            if ($uploadId) {
                $upload = Upload::query()
                    ->where('id', $uploadId)
                    ->where('uploadable_type', 'App\Tender')
                    ->where('uploadable_id', $tender->id)
                    ->first();

                if ($upload) {
                    $prev = $previousByUploadId->get($uploadId);
                    $pathUnchanged = $prev && ($prev['path'] ?? '') === ($doc['path'] ?? '');
                    $existingPath = method_exists($upload, 'resolveAbsolutePath')
                        ? $upload->resolveAbsolutePath()
                        : (is_file($upload->getPath()) ? $upload->getPath() : null);

                    if ($pathUnchanged && $existingPath) {
                        $upload->label = $nama;
                        $upload->save();
                        $keptUploadIds[] = (int) $upload->id;
                        $updated[] = array_merge($doc, [
                            'upload_id' => (int) $upload->id,
                            'nama' => $nama,
                        ]);
                        continue;
                    }

                    $this->deleteTenderUpload($upload);
                }
            }

            $upload = $this->createTenderMejaUpload($tender, $sourcePath, $nama);
            $keptUploadIds[] = (int) $upload->id;
            $updated[] = array_merge($doc, [
                'upload_id' => (int) $upload->id,
                'nama' => $nama,
                'url' => $upload->getUrl(),
            ]);
        }

        $previousUploadIds = collect($previousDocuments)
            ->pluck('upload_id')
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->all();

        foreach (array_diff($previousUploadIds, $keptUploadIds) as $orphanId) {
            $upload = Upload::query()->find($orphanId);
            if ($upload && (int) $upload->uploadable_id === (int) $tender->id) {
                $this->deleteTenderUpload($upload);
            }
        }

        return $updated;
    }

    protected function createTenderMejaUpload(Tender $tender, string $sourcePath, string $label): Upload
    {
        $hash = Str::random(40);
        $relativeDir = 'uploads/' . $hash;
        $destDir = public_path($relativeDir . '/');
        if (! is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }

        $filename = basename($sourcePath);
        if (! copy($sourcePath, $destDir . $filename)) {
            throw new \RuntimeException('Gagal menyalin fail dokumen meja terkawal.');
        }

        $fullPath = $destDir . $filename;
        $mime = mime_content_type($fullPath) ?: 'application/octet-stream';

        $upload = new Upload();
        $upload->fill([
            'name' => $filename,
            'size' => filesize($fullPath),
            'type' => $mime,
            'public' => 1,
            'label' => $label,
            // Store relative path so downloads survive deploy path changes.
            'path' => $relativeDir . '/',
            'url' => url($relativeDir),
            'uploadable_type' => 'App\Tender',
            'uploadable_id' => $tender->id,
        ]);
        $upload->save();

        if ($mime === 'application/pdf' && method_exists($tender, 'setWatermark')) {
            try {
                $tender->setWatermark($fullPath, public_path('images/etender-dokumen-watermark.png'));
            } catch (\Throwable) {
                // Non-fatal: dokumen tetap disimpan tanpa watermark.
            }
        }

        return $upload;
    }

    protected function deleteTenderUpload(Upload $upload): void
    {
        $dir = method_exists($upload, 'resolveAbsolutePath') && $upload->resolveAbsolutePath()
            ? dirname($upload->resolveAbsolutePath())
            : rtrim((string) $upload->path, '/');

        if ($dir !== '' && ! str_starts_with($dir, '/') && ! preg_match('/^[A-Za-z]:/', $dir)) {
            $dir = public_path($dir);
        }

        if ($dir !== '' && is_dir($dir)) {
            foreach (glob($dir . '/*') ?: [] as $file) {
                if (is_file($file)) {
                    @unlink($file);
                }
            }
            @rmdir($dir);
        }

        $upload->delete();
    }

    /**
     * Backfill penyediaan iklan dokumen into tender uploads when viewing a tender.
     */
    public function ensureMejaTerkawalSyncedForTender(Tender $tender): void
    {
        $record = PenyediaanIklan::query()->where('tender_id', $tender->id)->first();
        if (! $record || ! is_array($record->meta)) {
            return;
        }

        $docs = $record->meta['iklan']['dokumen_sokongan'] ?? [];
        if ($docs === []) {
            return;
        }

        $needsSync = collect($docs)->contains(function ($doc) {
            if (empty($doc['upload_id'])) {
                return true;
            }

            $upload = Upload::query()->find($doc['upload_id']);
            if (! $upload) {
                return true;
            }

            if (method_exists($upload, 'resolveAbsolutePath')) {
                return $upload->resolveAbsolutePath() === null;
            }

            return ! is_file($upload->getPath());
        });

        if (! $needsSync) {
            return;
        }

        DB::transaction(function () use ($tender, $record, $docs) {
            $synced = $this->syncDokumenMejaToTenderUploads($tender, $docs, $docs);
            $meta = $record->meta;
            $meta['iklan']['dokumen_sokongan'] = $synced;
            $record->meta = $meta;
            $record->save();
        });

        $tender->unsetRelation('files');
    }
}
