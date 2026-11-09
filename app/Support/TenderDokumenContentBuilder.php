<?php

namespace App\Support;

use App\Models\FinancialChecklistFile;
use App\Models\FinancialChecklistItem;
use App\Models\KewanganKerjaItem;
use App\Models\SpesifikasiKerjaHeader;
use App\Models\SpesifikasiKerjaItem;
use App\Models\SpesifikasiKerjaFile;
use App\Models\StandardChecklistItem;
use App\Models\TechnicalChecklistFile;
use App\Models\TechnicalChecklistItem;
use App\Models\TechnicalSpecificationDetail;
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
        $physicalSubmission = (bool) $item->penghantaran_fizikal;
        if ($section === 'technical'
            && $item instanceof TechnicalChecklistItem
            && $sourceType === 'specification_document'
            && $item->specificationDocument) {
            $physicalSubmission = (bool) $item->specificationDocument->physical_submission;
        }
        if ((int) $this->tender->penilaian_fizikal !== 1
            && ($mechanism === 'serahan_tangan' || $vendorAction === 'serahan_tangan')) {
            // Older checklist rows may still contain the hand-delivery mechanism.
            // Treat them as ordinary vendor uploads when this tender has it disabled.
            $mechanism = 'petender_muat_naik';
            $vendorAction = 'muat_naik';
        }
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
            'penghantaran_fizikal' => in_array($section, ['technical', 'financial', 'kewangan_kerja'], true)
                && (int) $this->tender->penilaian_fizikal === 1
                && $physicalSubmission,
            'action' => $action,
            'tindakan' => $label,
            'badge_class' => VendorActionLabel::badgeClass($label),
            'source' => $section,
            'status' => $item->status ?? 'draft',
            'admin_content' => $adminContent,
        ];
    }

    /**
     * One dokumen row for the whole kerja specification list (ABC, DEF, … as table rows).
     *
     * @param  \Illuminate\Support\Collection<int, SpesifikasiKerjaItem>  $items
     * @return array<string, mixed>
     */
    public function buildForSpesifikasiKerjaHeader(SpesifikasiKerjaHeader $header, $items): array
    {
        $rows = [];
        $bil = 0;

        foreach ($items as $item) {
            $bil++;
            $rows[] = [
                'kind' => 'item',
                'bil' => $bil,
                'item_uuid' => $item->uuid,
                'title' => $item->nama_item ?: $item->spesifikasi,
                'unit' => null,
                'kuantiti' => null,
                'quantity' => null,
                'kadar' => null,
                'jumlah' => null,
                'ya_tidak' => null,
                'catatan' => null,
                'files' => [],
            ];

            foreach ($item->specs as $spec) {
                $rows[] = [
                    'kind' => 'spec',
                    'bil' => null,
                    'item_uuid' => $spec->uuid,
                    'title' => $spec->spesifikasi,
                    'unit' => $spec->unit,
                    'kuantiti' => $spec->kuantiti,
                    'quantity' => $spec->kuantiti,
                    // PTJ reference rate (read-only context); vendor offer uses item_prices.
                    'kadar' => $spec->kadar,
                    'jumlah' => $spec->jumlah(),
                    'ya_tidak' => $spec->ya_tidak,
                    'catatan' => $spec->catatan,
                    'files' => $spec->files->map(fn (SpesifikasiKerjaFile $file) => [
                        'uuid' => $file->uuid,
                        'name' => $file->original_name,
                        'url' => route('tenderDokumen.spesifikasiKerjaFile', [
                            'tender' => $this->tender->id,
                            'fileUuid' => $file->uuid,
                        ]),
                        'mime_type' => $file->mime_type,
                        'size' => $file->size,
                    ])->values()->all(),
                ];
            }
        }

        return [
            'uuid' => $header->uuid,
            'title' => 'Senarai Spesifikasi',
            'nama' => 'Senarai Spesifikasi',
            'section' => 'spesifikasi_kerja',
            'source_type' => 'specification',
            'mechanism' => null,
            'vendor_action' => null,
            'action' => 'view_specification',
            'tindakan' => 'Spesifikasi',
            'badge_class' => VendorActionLabel::badgeClass('Spesifikasi'),
            'source' => 'spesifikasi_kerja',
            'status' => $header->status ?? 'draft',
            'admin_content' => [
                'type' => 'specification_table',
                'document_title' => 'Senarai Spesifikasi',
                'rows' => $rows,
                'files' => [],
                'form' => null,
                'note' => null,
            ],
        ];
    }

    /**
     * Build the vendor checklist row for the officer's downloadable BQ template.
     *
     * @return array<string, mixed>
     */
    public function buildForBqTemplate(SpesifikasiKerjaHeader $header, SpesifikasiKerjaFile $template): array
    {
        $hash = sha1('suksel-bq-template:' . $header->uuid);
        $variant = dechex((hexdec($hash[16]) & 0x3) | 0x8);
        $itemUuid = substr($hash, 0, 8) . '-' . substr($hash, 8, 4) . '-5' . substr($hash, 13, 3)
            . '-' . $variant . substr($hash, 17, 3) . '-' . substr($hash, 20, 12);

        return [
            'uuid' => $itemUuid,
            'title' => 'Jadual Harga & Kuantiti',
            'nama' => 'Jadual Harga & Kuantiti',
            'section' => 'spesifikasi_kerja',
            'source_type' => 'bq_template',
            'mechanism' => null,
            'vendor_action' => 'download_upload',
            'action' => 'download_upload',
            'tindakan' => 'Muat Turun & Muat Naik BQ',
            'badge_class' => VendorActionLabel::badgeClass('Muat Turun & Muat Naik BQ'),
            'source' => 'bq_template',
            'status' => $header->status ?? 'draft',
            'admin_content' => [
                'type' => 'files',
                'document_title' => 'Dokumen Bill of Quantities',
                'note' => 'Muat turun templat, lengkapkan di luar sistem dan muat naik BQ yang telah siap.',
                'files' => [[
                    'uuid' => $template->uuid,
                    'name' => $template->original_name,
                    'url' => route('tenderChecklist.download', [
                        'tender' => $this->tender->id,
                        'section' => 'spesifikasi_kerja',
                        'fileUuid' => $template->uuid,
                    ]),
                    'mime_type' => $template->mime_type,
                    'size' => (int) $template->size,
                ]],
                'form' => null,
                'bq_template_mode' => true,
                'template_uuid' => $template->uuid,
                'header_uuid' => $header->uuid,
                'legacy_item_uuid' => $header->uuid,
            ],
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
            'title' => $item->nama_item ?: ($item->spesifikasi ?? ''),
            'nama' => $item->nama_item ?: ($item->spesifikasi ?? ''),
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
                    'title' => $item->nama_item ?: $item->spesifikasi,
                    'quantity' => $item->kuantiti,
                    'unit' => $item->unit,
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

        if ($action === 'serahan_tangan') {
            $content['type'] = 'serahan_tangan';
            $content['note'] = 'Petender perlu menyerahkan dokumen secara serahan tangan.';

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
        if (
            ! empty($apiOverlay['specification_rows'])
            && is_array($apiOverlay['specification_rows'])
            && count(array_filter($apiOverlay['specification_rows'])) > 0
        ) {
            $rows = array_values($apiOverlay['specification_rows']);
            foreach ($rows as &$row) {
                if (($row['kind'] ?? '') !== 'detail') {
                    continue;
                }
                if (! empty($row['owner_pematuhan']) || ! empty($row['ya_tidak']) || ! empty($row['pematuhan'])) {
                    continue;
                }
                if (($row['response_type'] ?? '') === 'yes_no') {
                    $row['owner_pematuhan'] = 'yes';
                }
            }
            unset($row);

            return [
                'document_title' => $apiOverlay['document_title'] ?? $item->title,
                'rows' => $rows,
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
                    'kadar_harga' => null,
                ]],
            ];
        }

        $document = TechnicalSpecificationDocument::query()->find($documentId);
        $items = TechnicalSpecificationItem::query()
            ->with(['details.scoreRules'])
            ->where('technical_specification_document_id', $documentId)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $rows = [];
        $itemIndex = 0;

        foreach ($items as $item) {
            $itemIndex++;
            $rows[] = [
                'kind' => 'item',
                'bil' => (string) $itemIndex,
                'item_uuid' => $item->uuid,
                'title' => $item->title,
                'quantity' => $item->quantity,
                'unit' => $item->unit,
                'kadar_harga' => $item->kadar_harga,
                'response_type' => null,
            ];

            $detailIndex = 0;
            foreach ($item->details as $detail) {
                $detailIndex++;
                $rows[] = [
                    'kind' => 'detail',
                    'bil' => $itemIndex . '.' . $detailIndex,
                    'item_uuid' => $item->uuid,
                    'detail_uuid' => $detail->uuid,
                    'title' => $detail->description,
                    'quantity' => null,
                    'unit' => null,
                    'kadar_harga' => null,
                    'response_type' => $detail->response_type,
                    // Owner expected pematuhan (Ya/Tidak) — single value, not the skema type label.
                    'owner_pematuhan' => $this->resolveOwnerPematuhan($detail),
                ];
            }
        }

        return [
            'document_title' => $document?->title ?: $documentTitle,
            'rows' => $rows ?: [[
                'kind' => 'item',
                'bil' => '1',
                'title' => $documentTitle,
                'quantity' => null,
                'unit' => null,
                'kadar_harga' => null,
                'response_type' => null,
            ]],
        ];
    }

    /**
     * Resolve owner's expected pematuhan as a single yes/no value (not the skema type label).
     * Prefers the boolean score-rule answer with the higher score (typically Ya).
     */
    protected function resolveOwnerPematuhan(TechnicalSpecificationDetail $detail): ?string
    {
        if (($detail->response_type ?? '') !== 'yes_no') {
            return null;
        }

        $rules = $detail->relationLoaded('scoreRules')
            ? $detail->scoreRules
            : $detail->scoreRules()->get();

        $best = null;
        $bestScore = null;
        foreach ($rules as $rule) {
            $answer = strtolower(trim((string) ($rule->answer_value ?? '')));
            if (! in_array($answer, ['yes', 'no', 'ya', 'tidak'], true)) {
                continue;
            }
            $score = (float) ($rule->score ?? 0);
            if ($bestScore === null || $score > $bestScore) {
                $bestScore = $score;
                $best = in_array($answer, ['yes', 'ya'], true) ? 'yes' : 'no';
            }
        }

        // Owner chose Ya/Tidak skema; default expected compliance is Ya when no skor rules saved.
        return $best ?? 'yes';
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
            if (! str_contains($base['url'], 'modal=1')) {
                $base['url'] .= '&modal=1';
            }
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
                ->map(fn ($file) => $this->mapChecklistFile($file, $section))
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

        return collect($files)->map(function ($file) use ($section) {
            $data = $file instanceof \Illuminate\Database\Eloquent\Model
                ? $file->toArray()
                : (is_object($file) ? (array) $file : (array) $file);

            return $this->mapChecklistFile($data, $section);
        })->values()->all();
    }

    /**
     * @param  array<string, mixed>|object  $file
     * @return array{uuid: ?string, name: string, url: string}
     */
    protected function mapChecklistFile(array|object $file, string $section): array
    {
        $uuid = is_object($file) ? ($file->uuid ?? null) : ($file['uuid'] ?? null);
        $name = is_object($file)
            ? ($file->original_name ?? $file->name ?? 'Dokumen')
            : ($file['original_name'] ?? $file['name'] ?? 'Dokumen');
        $path = is_object($file) ? ($file->path ?? null) : ($file['path'] ?? null);
        $url  = is_object($file) ? ($file->url ?? null) : ($file['url'] ?? null);

        if (! empty($uuid) && in_array($section, ['technical', 'financial', 'kewangan_kerja'], true)) {
            return [
                'uuid' => (string) $uuid,
                'name' => (string) $name,
                'url' => route('tenderChecklist.download', [
                    'tender' => $this->tender->id,
                    'section' => $section,
                    'fileUuid' => (string) $uuid,
                ]),
            ];
        }

        return [
            'uuid' => $uuid ? (string) $uuid : null,
            'name' => (string) $name,
            'url' => $url ?? $this->resolveFileUrl($path ?? '#'),
        ];
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

        $normalized = ltrim(str_replace('\\', '/', $path), '/');

        if (str_starts_with($normalized, 'storage/')) {
            return url('/' . $normalized);
        }

        if (\App\Support\AttachmentStorage::exists($normalized)) {
            return url('/storage/' . $normalized);
        }

        return url('/' . $normalized);
    }

    protected function guessActionUrlFromTitle(string $title): ?string
    {
        $normalized = strtolower(trim($title));

        return match (true) {
            str_contains($normalized, 'pengalaman kerja') => '/pengalaman-kerja',
            str_contains($normalized, 'kakitangan teknikal') => '/kakitangan-teknikal',
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
