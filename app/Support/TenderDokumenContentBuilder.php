<?php

namespace App\Support;

use App\Models\FinancialChecklistFile;
use App\Models\FinancialChecklistItem;
use App\Models\KewanganKerjaItem;
use App\Models\SpesifikasiKerjaItem;
use App\Models\StandardChecklistItem;
use App\Models\TechnicalChecklistFile;
use App\Models\TechnicalChecklistItem;
use App\Models\TechnicalSpecificationDocument;
use App\Models\TechnicalSpecificationItem;
use App\Services\OnlineFormStatusService;
use App\Tender;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TenderDokumenContentBuilder
{
    public function __construct(
        protected Tender $tender,
        protected ?OnlineFormStatusService $onlineFormStatus = null,
    ) {
        $this->onlineFormStatus ??= app(OnlineFormStatusService::class);
    }

    /**
     * @param  array<string, mixed>  $apiOverlay  Optional STOS API item keyed by uuid
     * @return array<string, mixed>
     */
    public function buildForChecklistItem(
        TechnicalChecklistItem|FinancialChecklistItem|KewanganKerjaItem $item,
        string $section,
        array $apiOverlay = []
    ): array {
        $sourceType = $item->source_type ?? 'manual';
        $mechanism = $item->mechanism ?? null;
        $vendorAction = $item->vendor_action ?? null;
        $action = TenderDokumenActionResolver::resolve($sourceType, $mechanism, $vendorAction);
        $label = VendorActionLabel::resolve($sourceType, $mechanism, $vendorAction);

        $adminContent = $this->buildAdminContent($item, $section, $action, $apiOverlay);

        return [
            'uuid' => $item->uuid ?? null,
            'title' => $item->title ?? '',
            'nama' => $item->title ?? '',
            'section' => $section,
            'source_type' => $sourceType,
            'mechanism' => $mechanism,
            'vendor_action' => $vendorAction,
            'action' => $action,
            'tindakan' => $label,
            'badge_class' => VendorActionLabel::badgeClass($label),
            'source' => $section,
            'status' => $item->status ?? 'draft',
            'admin_content' => $adminContent,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function buildForSpesifikasiKerjaItem(SpesifikasiKerjaItem $item, int $index): array
    {
        $label = 'Spesifikasi';

        return [
            'uuid' => $item->uuid ?? null,
            'title' => $item->spesifikasi ?? '',
            'nama' => $item->spesifikasi ?? '',
            'section' => 'spesifikasi_kerja',
            'source_type' => 'specification',
            'mechanism' => null,
            'vendor_action' => null,
            'action' => 'view_specification',
            'tindakan' => $label,
            'badge_class' => VendorActionLabel::badgeClass($label),
            'source' => 'spesifikasi_kerja',
            'status' => 'submitted',
            'admin_content' => [
                'type' => 'specification_table',
                'document_title' => null,
                'rows' => [[
                    'bil' => $index + 1,
                    'title' => $item->spesifikasi,
                    'quantity' => null,
                    'unit' => null,
                    'ya_tidak' => $item->ya_tidak,
                    'catatan' => $item->catatan,
                ]],
                'files' => [],
                'form' => null,
                'note' => null,
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $apiOverlay
     * @return array<string, mixed>
     */
    protected function buildAdminContent(
        TechnicalChecklistItem|FinancialChecklistItem|KewanganKerjaItem $item,
        string $section,
        string $action,
        array $apiOverlay = []
    ): array {
        $content = [
            'type' => null,
            'document_title' => null,
            'rows' => [],
            'files' => [],
            'form' => null,
            'note' => null,
        ];

        if ($action === 'view_specification') {
            $content['type'] = 'specification_table';
            $content = array_merge($content, $this->specificationContent($item, $apiOverlay));

            return $content;
        }

        if ($action === 'online_form') {
            $content['type'] = 'online_form';
            $content['form'] = $this->onlineFormContent($item, $apiOverlay);

            return $content;
        }

        if (in_array($action, ['download_only', 'download_upload', 'vendor_upload'], true)) {
            $content['type'] = 'files';
            $content['files'] = $this->fileContent($item, $section, $apiOverlay);
            $content['note'] = match ($action) {
                'vendor_upload' => 'Petender perlu muat naik dokumen selepas membeli tender.',
                'download_upload' => 'Petender perlu muat turun dokumen PTJ dan muat naik semula.',
                'download_only' => 'Petender perlu muat turun dokumen PTJ.',
                default => null,
            };

            return $content;
        }

        if ($action === 'key_in') {
            $content['type'] = 'key_in';
            $content['note'] = 'Petender perlu mengisi maklumat dalam borang ini.';

            return $content;
        }

        return $content;
    }

    /**
     * @param  array<string, mixed>  $apiOverlay
     * @return array{document_title: ?string, rows: array<int, array<string, mixed>>}
     */
    protected function specificationContent(
        TechnicalChecklistItem|FinancialChecklistItem|KewanganKerjaItem $item,
        array $apiOverlay = []
    ): array {
        if (! empty($apiOverlay['specification_rows']) && is_array($apiOverlay['specification_rows'])) {
            return [
                'document_title' => $apiOverlay['document_title'] ?? $item->title,
                'rows' => $apiOverlay['specification_rows'],
            ];
        }

        $documentId = null;
        $documentTitle = $item->title;

        if ($item instanceof TechnicalChecklistItem && $item->specification_document_id) {
            $documentId = $item->specification_document_id;
        }

        if ($item instanceof FinancialChecklistItem && $item->technical_item_id) {
            $technical = TechnicalChecklistItem::query()->find($item->technical_item_id);
            if ($technical?->specification_document_id) {
                $documentId = $technical->specification_document_id;
                $documentTitle = $technical->title ?: $documentTitle;
            }
        }

        if (! $documentId) {
            return [
                'document_title' => $documentTitle,
                'rows' => [[
                    'bil' => 1,
                    'title' => $item->title,
                    'quantity' => null,
                    'unit' => null,
                ]],
            ];
        }

        $document = TechnicalSpecificationDocument::query()->find($documentId);
        $rows = TechnicalSpecificationItem::query()
            ->where('technical_specification_document_id', $documentId)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->values()
            ->map(fn (TechnicalSpecificationItem $row, int $i) => [
                'bil' => $i + 1,
                'title' => $row->title,
                'quantity' => $row->quantity,
                'unit' => $row->unit,
            ])
            ->all();

        return [
            'document_title' => $document?->title ?: $documentTitle,
            'rows' => $rows ?: [[
                'bil' => 1,
                'title' => $documentTitle,
                'quantity' => null,
                'unit' => null,
            ]],
        ];
    }

    /**
     * @param  array<string, mixed>  $apiOverlay
     * @return array{label: string, url: ?string, form_key: ?string, status: string, status_class: string, status_key: string, summary: ?string, return_url: string}|null
     */
    protected function onlineFormContent(
        TechnicalChecklistItem|FinancialChecklistItem|KewanganKerjaItem $item,
        array $apiOverlay = []
    ): ?array {
        $actionUrl = $apiOverlay['action_url'] ?? null;
        $standardUuid = $apiOverlay['standard_item_uuid'] ?? null;

        if (! $actionUrl && $item->standard_item_id) {
            $standard = StandardChecklistItem::query()->find($item->standard_item_id);
            $actionUrl = $standard?->action_url;
        }

        if (! $actionUrl && $standardUuid) {
            $standard = StandardChecklistItem::query()->where('uuid', $standardUuid)->first();
            $actionUrl = $standard?->action_url;
        }

        $actionUrl = $actionUrl ?: $this->guessActionUrlFromTitle($item->title ?? '');

        $url = TenderDokumenFormRoutes::resolve($actionUrl, $this->tender);
        $formKey = $this->onlineFormStatus->resolveFormKey($actionUrl, $item->title ?? '');
        $adminStatus = $formKey
            ? $this->onlineFormStatus->adminStatus($this->tender, $formKey)
            : $this->onlineFormStatus->formatStatus($item->status ?? 'draft');

        return [
            'label' => $item->title,
            'url' => $url,
            'form_key' => $formKey,
            'status' => $adminStatus['label'],
            'status_class' => $adminStatus['status_class'],
            'status_key' => $adminStatus['status'],
            'summary' => $adminStatus['summary'],
            'return_url' => route('tenders.show', $this->tender->id),
        ];
    }

    /**
     * @param  array<string, mixed>  $apiOverlay
     * @return array<string, mixed>|null
     */
    public function onlineFormContentForVendor(
        TechnicalChecklistItem|FinancialChecklistItem|KewanganKerjaItem $item,
        array $apiOverlay,
        int $vendorId
    ): ?array {
        $base = $this->onlineFormContent($item, $apiOverlay);
        if (! $base) {
            return null;
        }

        if (! empty($base['form_key'])) {
            $vendorStatus = $this->onlineFormStatus->vendorStatus($this->tender, $vendorId, $base['form_key']);
            $base['status'] = $vendorStatus['label'];
            $base['status_class'] = $vendorStatus['status_class'];
            $base['status_key'] = $vendorStatus['status'];
            $base['summary'] = $vendorStatus['summary'] ?? $base['summary'];
        }

        $base['return_url'] = route('tenders.show', $this->tender->id) . '#vt-dokumen-tawaran';

        if (! empty($base['url'])) {
            $separator = str_contains($base['url'], '?') ? '&' : '?';
            $base['url'] .= $separator . 'return=' . urlencode($base['return_url']);
        }

        return $base;
    }

    /**
     * @param  array<string, mixed>  $apiOverlay
     * @return array<int, array{uuid: ?string, name: string, url: string}>
     */
    protected function fileContent(
        TechnicalChecklistItem|FinancialChecklistItem|KewanganKerjaItem $item,
        string $section,
        array $apiOverlay = []
    ): array {
        if (! empty($apiOverlay['files']) && is_array($apiOverlay['files'])) {
            return collect($apiOverlay['files'])
                ->map(fn ($file) => [
                    'uuid' => $file['uuid'] ?? null,
                    'name' => $file['original_name'] ?? $file['name'] ?? 'Dokumen',
                    'url' => $file['url'] ?? $this->resolveFileUrl($file['path'] ?? '#'),
                ])
                ->values()
                ->all();
        }

        $files = match ($section) {
            'technical' => TechnicalChecklistFile::query()
                ->where('technical_checklist_item_id', $item->id)
                ->orderBy('id')
                ->get(),
            'financial' => FinancialChecklistFile::query()
                ->where('financial_checklist_item_id', $item->id)
                ->orderBy('id')
                ->get(),
            'kewangan_kerja' => DB::table('kewangan_kerja_files')
                ->where('kewangan_kerja_item_id', $item->id)
                ->orderBy('id')
                ->get(),
            default => collect(),
        };

        return collect($files)->map(function ($file) {
            $file = is_object($file) ? $file : (object) $file;

            return [
                'uuid' => $file->uuid ?? null,
                'name' => $file->original_name ?? 'Dokumen',
                'url' => $this->resolveFileUrl($file->path ?? '#'),
            ];
        })->values()->all();
    }

    protected function resolveFileUrl(?string $path): string
    {
        $path = trim((string) $path);
        if ($path === '' || $path === '#') {
            return '#';
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return asset(ltrim($path, '/'));
    }

    protected function guessActionUrlFromTitle(string $title): ?string
    {
        $normalized = strtolower(trim($title));

        return match (true) {
            str_contains($normalized, 'pengalaman kerja') => '/pengalaman-kerja',
            str_contains($normalized, 'kerja dalam tangan') => '/kerja-dalam-tangan',
            str_contains($normalized, 'profil petender') => '/profil-petender',
            str_contains($normalized, 'penyata bank') => '/penyata-bank',
            str_contains($normalized, 'lembaran imbangan') => '/lembaran-imbangan',
            str_contains($normalized, 'bon') && str_contains($normalized, 'saham') => '/bon-atau-saham',
            str_contains($normalized, 'prestasi kerja') => '/prestasi-kerja-semasa-petender',
            default => null,
        };
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $apiItems
     * @return array<string, array<string, mixed>>
     */
    public static function overlayByUuid(Collection $apiItems): array
    {
        return $apiItems
            ->filter(fn ($item) => is_array($item) && ! empty($item['uuid']))
            ->keyBy('uuid')
            ->all();
    }
}
