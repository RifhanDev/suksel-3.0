<?php

namespace App\Support;

use App\Models\PenyediaanIklan;
use App\Tender;

class TenderMejaTerkawalPresenter
{
    public const TAB_LABEL = 'Dokumen Meja Terawal';

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
        $fromUploads = $this->tender->tableFiles
            ->map(fn ($upload) => [
                'label' => trim((string) ($upload->label ?: $upload->name)),
                'url' => $upload->getUrl(),
                'size' => $this->formatSize($upload->size),
                'type' => $upload->type ?: '-',
            ])
            ->filter(fn (array $doc) => $doc['label'] !== '' && $doc['url'] !== '')
            ->values()
            ->all();

        if ($fromUploads !== []) {
            return $fromUploads;
        }

        return $this->documentsFromPenyediaanMeta();
    }

    /**
     * @return array<int, array{label: string, url: string, size: string|null, type: string|null}>
     */
    protected function documentsFromPenyediaanMeta(): array
    {
        $record = PenyediaanIklan::query()->where('tender_id', $this->tender->id)->first();
        if (! $record || ! is_array($record->meta)) {
            return [];
        }

        $docs = $record->meta['iklan']['dokumen_sokongan'] ?? [];
        $normalized = [];

        foreach ($docs as $doc) {
            if (! is_array($doc)) {
                continue;
            }

            $path = trim((string) ($doc['path'] ?? ''));
            $url = trim((string) ($doc['url'] ?? ''));
            if ($url === '' && $path !== '') {
                $url = asset($path);
            }

            $label = trim((string) ($doc['nama'] ?? $doc['original_name'] ?? ''));
            if ($label === '' || $url === '') {
                continue;
            }

            $fullPath = $path !== '' ? public_path($path) : null;
            $size = null;
            $type = null;
            if ($fullPath && is_file($fullPath)) {
                $size = (string) filesize($fullPath);
                $type = mime_content_type($fullPath) ?: null;
            }

            $normalized[] = [
                'label' => $label,
                'url' => $url,
                'size' => $this->formatSize($size),
                'type' => $type ?: '-',
            ];
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
