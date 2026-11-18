<?php

namespace App\Support;

use App\Models\PenyediaanIklan;
use App\Tender;

class TenderMejaTerkawalPresenter
{
    public const TAB_LABEL = 'Dokumen Meja Terkawal';

    public function __construct(protected Tender $tender) {}

    public static function for(Tender $tender): self
    {
        return new self($tender);
    }

    public function hasDocuments(): bool
    {
        return count($this->documents()) > 0;
    }

    public function count(): int
    {
        return count($this->documents());
    }

    /**
     * @return array<int, array{label: string, url: string, size: string|null, type: string|null}>
     */
    public function documents(): array
    {
        $record = PenyediaanIklan::query()->where('tender_id', $this->tender->id)->first();
        if (! $record || ! is_array($record->meta)) {
            return [];
        }

        $docs = $record->meta['iklan']['dokumen_sokongan'] ?? [];
        if (! is_array($docs) || $docs === []) {
            return [];
        }

        $uploadIds = collect($docs)
            ->filter(fn ($doc) => is_array($doc) && ! empty($doc['upload_id']))
            ->pluck('upload_id')
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0)
            ->unique()
            ->values();

        $uploadsById = $uploadIds->isEmpty()
            ? collect()
            : $this->tender->files()
                ->where('public', 1)
                ->whereIn('id', $uploadIds)
                ->get()
                ->keyBy('id');

        $normalized = [];
        $seenUploadIds = [];
        $seenPaths = [];

        foreach ($docs as $doc) {
            if (! is_array($doc)) {
                continue;
            }

            $uploadId = (int) ($doc['upload_id'] ?? 0);
            if ($uploadId > 0 && isset($seenUploadIds[$uploadId])) {
                continue;
            }

            $path = trim((string) ($doc['path'] ?? ''));
            $normalizedPath = ltrim(str_replace('\\', '/', $path), '/');
            if ($uploadId <= 0 && $normalizedPath !== '' && isset($seenPaths[$normalizedPath])) {
                continue;
            }

            $upload = $uploadId > 0 ? $uploadsById->get($uploadId) : null;
            $label = trim((string) ($doc['nama'] ?? $doc['original_name'] ?? $upload?->label ?? $upload?->name ?? ''));
            if ($label === '') {
                continue;
            }

            if ($upload) {
                $download = method_exists($upload, 'downloadFilename')
                    ? $upload->downloadFilename()
                    : $label;
                $url = route('tenders.files', [$this->tender->id, $upload->id]);
                $size = $upload->size;
                $type = $upload->type;
            } else {
                $url = trim((string) ($doc['url'] ?? ''));
                if ($url === '' && $path !== '') {
                    $url = '/' . ltrim(str_replace('\\', '/', $path), '/');
                } elseif ($url !== '' && ! str_starts_with($url, '/') && ! str_starts_with($url, 'http://') && ! str_starts_with($url, 'https://')) {
                    $url = '/' . ltrim($url, '/');
                }

                $fullPath = $path !== '' ? public_path($path) : null;
                $size = $fullPath && is_file($fullPath) ? filesize($fullPath) : null;
                $type = $fullPath && is_file($fullPath) ? (mime_content_type($fullPath) ?: null) : null;
                $download = $label;
                if (pathinfo($download, PATHINFO_EXTENSION) === '') {
                    $ext = pathinfo((string) ($doc['original_name'] ?? $path), PATHINFO_EXTENSION);
                    if ($ext === '' && $type) {
                        $ext = \App\Upload::extensionFromMime((string) $type);
                    }
                    if ($ext === '') {
                        $ext = 'pdf';
                    }
                    $download .= '.' . strtolower($ext);
                }
            }

            if ($url === '') {
                continue;
            }

            $normalized[] = [
                'label' => $label,
                'download_name' => $download,
                'url' => $url,
                'size' => $this->formatSize($size),
                'type' => $type ?: '-',
            ];

            if ($uploadId > 0) {
                $seenUploadIds[$uploadId] = true;
            } elseif ($normalizedPath !== '') {
                $seenPaths[$normalizedPath] = true;
            }
        }

        return $normalized;
    }

    protected function formatSize(mixed $size): string
    {
        if ($size === null || $size === '' || $size === '-') {
            return '-';
        }

        if (is_string($size) && preg_match('/[a-zA-Z]/', $size)) {
            return $size;
        }

        $bytes = (int) $size;
        if ($bytes <= 0) {
            return '-';
        }

        $mb = $bytes / 1024 / 1024;
        if ($mb >= 1) {
            return number_format($mb, 2) . ' MB';
        }

        $kb = $bytes / 1024;
        if ($kb >= 1) {
            return number_format($kb, 2) . ' KB';
        }

        return $bytes . ' B';
    }
}
