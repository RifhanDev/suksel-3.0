<?php

namespace App\Support;

use App\Models\FinancialChecklistItem;
use App\Models\KewanganKerjaItem;
use App\Models\SpesifikasiKerjaHeader;
use App\Models\SpesifikasiKerjaItem;
use App\Models\TechnicalChecklistItem;
use App\Services\StosBackendClient;
use App\Tender;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class TenderDokumenPresenter
{
    protected ?array $apiOverlays = null;

    public function __construct(
        protected Tender $tender,
        protected TenderDokumenContentBuilder $contentBuilder,
    ) {}

    public static function for(Tender $tender): self
    {
        return new self($tender, new TenderDokumenContentBuilder($tender));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function items(string $mode = 'admin', ?int $vendorId = null): array
    {
        $this->loadApiOverlays();

        $items = $this->isKerjaCategory()
            ? $this->kerjaItems()
            : $this->bekalanPerkhidmatanItems();

        $vendorResponses = $vendorId
            ? app(\App\Services\VendorDokumenResponseService::class)->responsesByItemUuid($this->tender, $vendorId)
            : [];

        $vendorFormStatuses = $vendorId
            ? app(\App\Services\OnlineFormStatusService::class)->vendorStatusesForTender($this->tender, $vendorId)
            : [];

        return collect($items)
            ->map(function (array $item) use ($mode, $vendorId, $vendorResponses, $vendorFormStatuses) {
                if ($vendorId && ($item['action'] ?? '') === 'online_form') {
                    $formKey = $item['admin_content']['form']['form_key'] ?? null;
                    if ($formKey) {
                        $formStatus = $vendorFormStatuses[$formKey]
                            ?? app(\App\Services\OnlineFormStatusService::class)->formatStatus('draft');
                        $item['admin_content']['form']['status'] = $formStatus['label'];
                        $item['admin_content']['form']['status_class'] = $formStatus['status_class'];
                        $item['admin_content']['form']['status_key'] = $formStatus['status'];
                        $item['admin_content']['form']['summary'] = $formStatus['summary'];
                    }
                }

                if ($vendorId && ($item['action'] ?? '') === 'online_form' && ! empty($item['uuid'])) {
                    $item = $this->applyVendorOnlineFormContent($item, $vendorId);
                }

                $item['can_edit'] = $mode === 'vendor' && $vendorId !== null;
                $item['mode'] = $mode;

                if ($vendorId && ! empty($item['uuid']) && isset($vendorResponses[$item['uuid']])) {
                    $item['vendor_content'] = $vendorResponses[$item['uuid']];
                } else {
                    $item['vendor_content'] = ['key_in' => null, 'specification' => [], 'files' => [], 'status' => 'draft'];
                }

                if (($item['action'] ?? '') === 'view_specification' && ! empty($item['uuid'])) {
                    $item['vendor_content']['specification'] = $item['vendor_content']['specification'] ?? [];
                }

                $item['vendor_status'] = $this->resolveVendorItemStatus($item, $vendorResponses, $vendorFormStatuses);

                return $item;
            })
            ->values()
            ->all();
    }

    /**
     * @param  array<string, mixed>  $item
     * @return array<string, mixed>
     */
    protected function applyVendorOnlineFormContent(array $item, int $vendorId): array
    {
        $form = $item['admin_content']['form'] ?? null;
        if (! $form || empty($form['form_key'])) {
            return $item;
        }

        $vendorForm = $form;
        $vendorForm['return_url'] = route('tenders.show', $this->tender->id) . '#vt-dokumen-tawaran';
        if (! empty($vendorForm['url'])) {
            $separator = str_contains($vendorForm['url'], '?') ? '&' : '?';
            $vendorForm['url'] .= $separator . 'return=' . urlencode($vendorForm['return_url']);
            if (! str_contains($vendorForm['url'], 'modal=1')) {
                $vendorForm['url'] .= '&modal=1';
            }
        }

        $item['admin_content']['form'] = $vendorForm;

        return $item;
    }

    /**
     * @param  array<string, array<string, mixed>>  $vendorResponses
     * @param  array<string, array<string, mixed>>  $vendorFormStatuses
     */
    protected function resolveVendorItemStatus(array $item, array $vendorResponses, array $vendorFormStatuses): string
    {
        if (($item['action'] ?? '') === 'online_form') {
            $formKey = $item['admin_content']['form']['form_key'] ?? null;
            if ($formKey && ($vendorFormStatuses[$formKey]['status'] ?? '') === 'submitted') {
                return 'submitted';
            }
        }

        if (($item['action'] ?? '') === 'view_specification') {
            $uuid = $item['uuid'] ?? null;
            if ($uuid && isset($vendorResponses[$uuid])) {
                return $vendorResponses[$uuid]['status'] ?? 'draft';
            }
        }

        $uuid = $item['uuid'] ?? null;
        if ($uuid && isset($vendorResponses[$uuid])) {
            return $vendorResponses[$uuid]['status'] ?? 'draft';
        }

        return 'draft';
    }

    /**
     * Backward-compatible flat list (nama, tindakan, badge_class, source).
     *
     * @return array<int, array{nama: string, tindakan: string, badge_class: string, source: string}>
     */
    public function legacyItems(): array
    {
        return collect($this->items())
            ->map(fn (array $item) => [
                'nama' => $item['nama'] ?? $item['title'] ?? '',
                'tindakan' => $item['tindakan'] ?? '',
                'badge_class' => $item['badge_class'] ?? 'badge-status-neutral',
                'source' => $item['source'] ?? '',
            ])
            ->all();
    }

    protected function isKerjaCategory(): bool
    {
        return (int) ($this->tender->kategori_perolehan_id ?? 0) === 3;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function kerjaItems(): array
    {
        $spesifikasi = [];

        $header = SpesifikasiKerjaHeader::query()
            ->where('tender_id', $this->tender->id)
            ->with(['items' => fn ($query) => $query->orderBy('sort_order')->orderBy('id')])
            ->first();

        if ($header && $header->items->isNotEmpty()) {
            $spesifikasi[] = $this->contentBuilder->buildForSpesifikasiKerjaHeader($header, $header->items);
        }

        $kewangan = KewanganKerjaItem::query()
            ->whereHas('header', fn ($query) => $query->where('tender_id', $this->tender->id))
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (KewanganKerjaItem $item) => $this->contentBuilder->buildForChecklistItem(
                $item,
                'kewangan_kerja',
                $this->overlayFor($item->uuid)
            ))
            ->all();

        return array_merge($spesifikasi, $kewangan);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function bekalanPerkhidmatanItems(): array
    {
        $technical = TechnicalChecklistItem::query()
            ->whereHas('header', fn ($query) => $query->where('tender_id', $this->tender->id))
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (TechnicalChecklistItem $item) => $this->contentBuilder->buildForChecklistItem(
                $item,
                'technical',
                $this->overlayFor($item->uuid)
            ))
            ->all();

        $financial = FinancialChecklistItem::query()
            ->whereHas('header', fn ($query) => $query->where('tender_id', $this->tender->id))
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (FinancialChecklistItem $item) => $this->contentBuilder->buildForChecklistItem(
                $item,
                'financial',
                $this->overlayFor($item->uuid)
            ))
            ->all();

        return array_merge($technical, $financial);
    }

    protected function loadApiOverlays(): void
    {
        if ($this->apiOverlays !== null) {
            return;
        }

        $this->apiOverlays = [
            'technical' => [],
            'financial' => [],
            'kewangan_kerja' => [],
        ];

        if (empty($this->tender->uuid)) {
            return;
        }

        $stos = app(StosBackendClient::class);
        if (! $stos->isConfigured()) {
            return;
        }

        try {
            if ($this->isKerjaCategory()) {
                $this->mergeApiSection($stos, '/api/kewangan-kerja/' . $this->tender->uuid, 'kewangan_kerja');

                return;
            }

            $this->mergeApiSection($stos, '/api/technical-checklists/' . $this->tender->uuid, 'technical');
            $this->mergeApiSection($stos, '/api/financial-checklists/' . $this->tender->uuid, 'financial');
        } catch (\Throwable $e) {
            Log::warning('TenderDokumenPresenter API overlay failed', [
                'tender_id' => $this->tender->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function mergeApiSection(StosBackendClient $stos, string $path, string $section): void
    {
        $response = $stos->get($path);
        if (! $response->successful()) {
            return;
        }

        $items = collect($response->json('data.items') ?? []);
        $this->apiOverlays[$section] = TenderDokumenContentBuilder::overlayByUuid($items);
    }

    /**
     * @return array<string, mixed>
     */
    protected function overlayFor(?string $uuid): array
    {
        if (! $uuid || $this->apiOverlays === null) {
            return [];
        }

        foreach ($this->apiOverlays as $sectionItems) {
            if (isset($sectionItems[$uuid])) {
                return $sectionItems[$uuid];
            }
        }

        return [];
    }
}
