<?php

namespace App\Services;

use App\Models\PenyediaanIklan;
use App\Support\AttachmentStorage;
use App\Support\PenyediaanIklanAccess;
use App\Support\TenderProcessStatus;
use App\Tender;
use App\Upload;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
            $tender = Tender::query()
                ->whereKey($tender->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (! PenyediaanIklanAccess::isEditable($tender)) {
                throw ValidationException::withMessages([
                    'tender' => 'Tender telah disiarkan atau tidak lagi boleh dikemaskini dalam Penyediaan Iklan.',
                ]);
            }

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

            // Keep tenders.allow_exception in sync on every draft/hantar save.
            // Vendor/agency screens read that column (canException), not meta.iklan.
            $this->syncKebenaranKhasToTender($tender, $payload);

            if ($submit) {
                $record->submitted_at = now();
                $this->applyPayloadToTender($tender, $payload);
            }

            $record->save();

            return $record;
        });
    }

    /**
     * Persist the "Kebenaran Khas" checkbox onto tenders.allow_exception.
     *
     * @param  array<string, mixed>  $payload
     */
    protected function syncKebenaranKhasToTender(Tender $tender, array $payload): void
    {
        $iklan = $payload['iklan'] ?? [];
        if (! array_key_exists('kebenaran_khas', $iklan)) {
            return;
        }

        Tender::query()->where('id', $tender->id)->update([
            'allow_exception' => ! empty($iklan['kebenaran_khas']) ? 1 : 0,
        ]);
        $tender->allow_exception = ! empty($iklan['kebenaran_khas']) ? 1 : 0;
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
     *
     * Reuses visit_id when present; otherwise matches an existing row by content
     * so a second Simpan/Hantar without visit_id (common after AJAX draft save)
     * does not create duplicate Lawatan Tapak lines.
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

        foreach ($taklimatRows as $index => $row) {
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

            // Fallback: same index in previous meta still has a visit_id.
            if (! $visit && empty($visitId)) {
                $prevId = $previousTaklimat[$index]['visit_id'] ?? null;
                if ($prevId) {
                    $visit = $tender->siteVisits()->find((int) $prevId);
                }
            }

            // Fallback: reuse an existing visit with identical content (race / lost visit_id).
            if (! $visit) {
                $visit = $tender->siteVisits()
                    ->where('meetpoint', $attrs['meetpoint'])
                    ->where('address', $attrs['address'])
                    ->where('datetime', $attrs['datetime'])
                    ->when($keptIds !== [], fn ($q) => $q->whereNotIn('id', $keptIds))
                    ->orderBy('id')
                    ->first();
            }

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

        $toDelete = array_values(array_unique(array_diff($previousIds, $keptIds)));

        // Also drop exact content duplicates left by parallel saves (same perihal/lokasi/masa).
        foreach ($updatedRows as $row) {
            $datetime = $this->parseTaklimatDatetime($row['tarikh'] ?? null, $row['masa'] ?? null);
            if ($datetime === null) {
                continue;
            }

            $dupIds = $tender->siteVisits()
                ->where('meetpoint', trim((string) ($row['perihal'] ?? '')) ?: '-')
                ->where('address', trim((string) ($row['lokasi'] ?? '')) ?: '-')
                ->where('datetime', $datetime)
                ->whereNotIn('id', $keptIds)
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->all();

            $toDelete = array_values(array_unique(array_merge($toDelete, $dupIds)));
        }

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
            'tender_rules' => isset($iklan['syarat_tender']) ? syarat_html($iklan['syarat_tender']) : null,
            'only_selangor' => isset($syarat['only_selangor']) ? (int) $syarat['only_selangor'] : null,
            'only_bumiputera' => array_key_exists('only_bumiputera', $syarat) ? (int) (bool) $syarat['only_bumiputera'] : null,
            'invitation' => array_key_exists('invitation', $syarat) ? (int) (bool) $syarat['invitation'] : null,
            'only_advertise' => array_key_exists('only_advertise', $syarat) ? (int) (bool) $syarat['only_advertise'] : null,
            'allow_exception' => array_key_exists('kebenaran_khas', $iklan)
                ? (int) (bool) $iklan['kebenaran_khas']
                : null,
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

            $sourcePath = $this->resolveLocalSourcePath((string) ($doc['path'] ?? ''));
            if ($sourcePath === null) {
                continue;
            }

            $uploadId = $doc['upload_id'] ?? null;
            if ($uploadId) {
                $upload = Upload::query()
                    ->where('id', $uploadId)
                    ->whereIn('uploadable_type', \App\Tender::uploadMorphTypes())
                    ->where('uploadable_id', $tender->id)
                    ->first();

                if ($upload) {
                    $prev = $previousByUploadId->get($uploadId);
                    $pathUnchanged = $prev && ($prev['path'] ?? '') === ($doc['path'] ?? '');
                    // Production pushes the tender copy to NAS then deletes the local file.
                    // resolveAbsolutePath() only checks local disk — also accept a live NAS object
                    // and reject size=0 rows so a re-sync can rebuild the upload.
                    $uploadHealthy = $pathUnchanged && $this->tenderMejaUploadIsHealthy($upload);

                    if ($uploadHealthy) {
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

            $upload = $this->createTenderMejaUpload(
                $tender,
                $sourcePath,
                $nama,
                (string) ($doc['original_name'] ?? $doc['path'] ?? '')
            );
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

    protected function createTenderMejaUpload(Tender $tender, string $sourcePath, string $label, string $originalName = ''): Upload
    {
        $hash = Str::random(40);
        $relativeDir = 'uploads/' . $hash;
        $destDir = public_path($relativeDir . '/');
        if (! is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }

        $filename = $this->mejaStoredFilename($sourcePath, $originalName, $label);
        if (! copy($sourcePath, $destDir . $filename)) {
            throw new \RuntimeException('Gagal menyalin fail dokumen meja terkawal.');
        }

        $fullPath = $destDir . $filename;
        $mime = mime_content_type($fullPath) ?: 'application/octet-stream';

        // tempnam() sources have no extension; sniff mime and rename before watermark/download.
        if (pathinfo($filename, PATHINFO_EXTENSION) === '') {
            $ext = Upload::extensionFromMime($mime) ?: 'pdf';
            $renamed = pathinfo($filename, PATHINFO_FILENAME) . '.' . $ext;
            if (@rename($fullPath, $destDir . $renamed)) {
                $filename = $renamed;
                $fullPath = $destDir . $filename;
            }
        }

        $sizeBeforeWatermark = (int) (@filesize($fullPath) ?: 0);

        if ($mime === 'application/pdf' && method_exists($tender, 'setWatermark')) {
            try {
                $tender->setWatermark($fullPath, public_path('images/etender-dokumen-watermark.png'));
            } catch (\Throwable $e) {
                // Non-fatal: keep original PDF. setWatermark restores _ori on failure.
                \Illuminate\Support\Facades\Log::warning('Meja terkawal watermark skipped', [
                    'tender_id' => $tender->id,
                    'file' => $fullPath,
                    'error' => $e->getMessage(),
                ]);
                $ori = \Illuminate\Support\Str::replace('.pdf', '_ori.pdf', $fullPath);
                if ((! is_file($fullPath) || filesize($fullPath) <= 0) && is_file($ori) && filesize($ori) > 0) {
                    @unlink($fullPath);
                    @rename($ori, $fullPath);
                }
            }
        }

        $size = (int) (@filesize($fullPath) ?: 0);
        if ($size <= 0 && $sizeBeforeWatermark > 0) {
            $size = $sizeBeforeWatermark;
        }
        if ($size <= 0) {
            @unlink($fullPath);
            throw new \RuntimeException('Dokumen meja terkawal kosong atau gagal disalin (saiz 0).');
        }

        // Push to NAS (perolehan_attachment) on production; no-op locally/staging.
        AttachmentStorage::pushPublicFile($fullPath);

        $upload = new Upload();
        $upload->fill([
            'name' => $filename,
            'size' => $size,
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

        return $upload;
    }

    protected function mejaStoredFilename(string $sourcePath, string $originalName, string $label): string
    {
        $filename = basename($sourcePath);
        if (pathinfo($filename, PATHINFO_EXTENSION) !== '') {
            return $filename;
        }

        foreach ([$originalName, $label] as $candidate) {
            $ext = pathinfo(trim((string) $candidate), PATHINFO_EXTENSION);
            if ($ext !== '') {
                return Str::uuid() . '.' . strtolower($ext);
            }
        }

        return Str::uuid() . '.pdf';
    }

    /**
     * Tender meja copy is healthy when DB size > 0 and the bytes exist locally or on NAS.
     * Size accessor formats bytes (e.g. "0.00 B") — always read the raw column.
     */
    protected function tenderMejaUploadIsHealthy(Upload $upload): bool
    {
        $rawSize = (int) ($upload->getRawOriginal('size') ?? $upload->getAttributes()['size'] ?? 0);
        if ($rawSize <= 0) {
            return false;
        }

        if (method_exists($upload, 'resolveAbsolutePath') && $upload->resolveAbsolutePath()) {
            return true;
        }

        $relative = AttachmentStorage::relativeForUpload(
            (string) ($upload->path ?? ''),
            (string) ($upload->name ?? '')
        );

        return $relative !== '' && AttachmentStorage::exists($relative);
    }

    protected function deleteTenderUpload(Upload $upload): void
    {
        $relative = AttachmentStorage::relativeForUpload(
            (string) ($upload->path ?? ''),
            (string) ($upload->name ?? '')
        );
        if ($relative !== '') {
            AttachmentStorage::delete($relative);
        }

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
     * Resolve a document path from public disk or NAS (baru / lama).
     */
    protected function resolveLocalSourcePath(string $path): ?string
    {
        $relative = AttachmentStorage::normalize($path);
        if ($relative === '') {
            return null;
        }

        $public = public_path($relative);
        if (is_file($public)) {
            return $public;
        }

        $located = AttachmentStorage::locate($relative);
        if ($located === null) {
            return null;
        }

        try {
            $contents = Storage::disk($located['disk'])->get($located['path']);
        } catch (\Throwable) {
            return null;
        }

        if ($contents === null || $contents === false || $contents === '') {
            return null;
        }

        $tmp = tempnam(sys_get_temp_dir(), 'meja_');
        if ($tmp === false) {
            return null;
        }

        $ext = pathinfo($relative, PATHINFO_EXTENSION);
        $target = $ext !== '' ? $tmp . '.' . strtolower($ext) : $tmp;
        if ($target !== $tmp) {
            @unlink($tmp);
        }

        if (file_put_contents($target, $contents) === false) {
            @unlink($target);

            return null;
        }

        return $target;
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

            return ! $this->tenderMejaUploadIsHealthy($upload);
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
