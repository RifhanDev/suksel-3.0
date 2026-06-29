<?php

namespace App\Services;

use App\Models\BonSaham;
use App\Models\LembaranImbangan;
use App\Models\PenyataBank;
use App\Models\ProfilPetender;
use App\Models\TenderPrestasiKerja;
use App\Models\TenderVendorOnlineFormStatus;
use App\Support\OnlineFormRegistry;
use App\Tender;

class OnlineFormStatusService
{
    public function __construct(protected StosBackendClient $stos) {}

    /**
     * @return array{status: string, label: string, status_class: string, summary: ?string}
     */
    public function adminStatus(Tender $tender, string $formKey): array
    {
        return match ($formKey) {
            'penyata_bank' => $this->fromRecordStatus(
                PenyataBank::query()->where('tender_id', $tender->id)->first()?->status,
                fn ($record) => $record ? 'Purata: RM ' . number_format((float) $record->purata, 2) : null,
                PenyataBank::query()->where('tender_id', $tender->id)->first()
            ),
            'profil_petender' => $this->fromRecordStatus(
                ProfilPetender::query()->where('tender_id', $tender->id)->first()?->status,
                fn ($record) => $record?->nama_syarikat,
                ProfilPetender::query()->where('tender_id', $tender->id)->first()
            ),
            'lembaran_imbangan' => $this->fromRecordStatus(
                LembaranImbangan::query()->where('tender_id', $tender->id)->first()?->status,
                fn ($record) => $record ? 'Wang tunai: RM ' . number_format((float) $record->wang_tunai, 2) : null,
                LembaranImbangan::query()->where('tender_id', $tender->id)->first()
            ),
            'bon_saham' => $this->fromRecordStatus(
                BonSaham::query()->where('tender_id', $tender->id)->first()?->status,
                fn ($record) => $record ? 'Jumlah: RM ' . number_format((float) $record->jumlah_keseluruhan, 2) : null,
                BonSaham::query()->where('tender_id', $tender->id)->first()
            ),
            'prestasi_kerja' => $this->fromRecordStatus(
                TenderPrestasiKerja::query()->where('tender_id', $tender->id)->first()?->status,
                function ($record) {
                    if (! $record) {
                        return null;
                    }
                    $count = $record->items()->count();

                    return $count > 0 ? "{$count} rekod prestasi" : null;
                },
                TenderPrestasiKerja::query()->withCount('items')->where('tender_id', $tender->id)->first()
            ),
            'pengalaman_kerja' => $this->stosCollectionStatus($tender, 'pengalaman-kerja', 'items', 'dokumens'),
            'kerja_dalam_tangan' => $this->stosCollectionStatus($tender, 'kerja-dalam-tangan', 'items'),
            default => $this->formatStatus('draft'),
        };
    }

    /**
     * @return array{status: string, label: string, status_class: string, summary: ?string}
     */
    public function vendorStatus(Tender $tender, int $vendorId, string $formKey): array
    {
        $record = TenderVendorOnlineFormStatus::query()
            ->where('tender_id', $tender->id)
            ->where('vendor_id', $vendorId)
            ->where('form_key', $formKey)
            ->first();

        if (! $record) {
            return $this->formatStatus('draft');
        }

        $formatted = $this->formatStatus($record->status);
        $formatted['summary'] = $record->summary['text'] ?? $record->summary['label'] ?? null;

        return $formatted;
    }

    /**
     * @return array<string, array{status: string, label: string, status_class: string, summary: ?string}>
     */
    public function vendorStatusesForTender(Tender $tender, int $vendorId): array
    {
        return TenderVendorOnlineFormStatus::query()
            ->where('tender_id', $tender->id)
            ->where('vendor_id', $vendorId)
            ->get()
            ->mapWithKeys(function (TenderVendorOnlineFormStatus $record) {
                $formatted = $this->formatStatus($record->status);
                $formatted['summary'] = $record->summary['text'] ?? null;

                return [$record->form_key => $formatted];
            })
            ->all();
    }

    /**
     * @param  array<string, mixed>  $summary
     */
    public function markSubmitted(Tender $tender, int $vendorId, string $formKey, array $summary = []): TenderVendorOnlineFormStatus
    {
        return $this->upsert($tender, $vendorId, $formKey, 'submitted', $summary, now());
    }

    /**
     * @param  array<string, mixed>  $summary
     */
    public function markDraft(Tender $tender, int $vendorId, string $formKey, array $summary = []): TenderVendorOnlineFormStatus
    {
        return $this->upsert($tender, $vendorId, $formKey, 'draft', $summary, null);
    }

    /**
     * @param  array<string, mixed>  $summary
     */
    protected function upsert(
        Tender $tender,
        int $vendorId,
        string $formKey,
        string $status,
        array $summary,
        $submittedAt
    ): TenderVendorOnlineFormStatus {
        $record = TenderVendorOnlineFormStatus::query()->firstOrNew([
            'tender_id' => $tender->id,
            'vendor_id' => $vendorId,
            'form_key' => $formKey,
        ]);

        if (! $record->exists) {
            $record->uuid = (string) \Illuminate\Support\Str::uuid();
        }

        $record->fill([
            'status' => $status,
            'summary' => $summary ?: $record->summary,
            'submitted_at' => $submittedAt,
            'updated_by' => auth()->id(),
        ])->save();

        return $record;
    }

    /**
     * @return array{status: string, label: string, status_class: string, summary: ?string}
     */
    protected function fromRecordStatus(?string $status, callable $summaryResolver, mixed $record): array
    {
        $formatted = $this->formatStatus($status ?: 'draft');
        $formatted['summary'] = $record ? $summaryResolver($record) : null;

        if ($formatted['status'] === 'draft' && $formatted['summary']) {
            $formatted = $this->formatStatus('in_progress');
            $formatted['summary'] = $summaryResolver($record);
        }

        return $formatted;
    }

    /**
     * @return array{status: string, label: string, status_class: string, summary: ?string}
     */
    protected function stosCollectionStatus(Tender $tender, string $endpoint, string ...$collections): array
    {
        if (! $this->stos->isConfigured() || empty($tender->uuid)) {
            return $this->formatStatus('draft');
        }

        $response = $this->stos->get('/api/' . $endpoint . '/' . $tender->uuid);
        if (! $response->successful()) {
            return $this->formatStatus('draft');
        }

        $data = $response->json('data') ?? [];
        $count = 0;
        foreach ($collections as $collection) {
            $count += count($data[$collection] ?? []);
        }

        if ($count === 0) {
            return $this->formatStatus('draft');
        }

        $formatted = $this->formatStatus(
            ($data['status'] ?? '') === 'submitted' ? 'submitted' : 'in_progress'
        );
        $formatted['summary'] = "{$count} rekod";

        return $formatted;
    }

    /**
     * @return array{status: string, label: string, status_class: string, summary: ?string}
     */
    public function formatStatus(string $status): array
    {
        return match ($status) {
            'submitted', 'complete', 'completed' => [
                'status' => 'submitted',
                'label' => 'Selesai',
                'status_class' => 'badge-status-success',
                'summary' => null,
            ],
            'in_progress', 'draft_with_data' => [
                'status' => 'in_progress',
                'label' => 'Dalam Proses',
                'status_class' => 'badge-status-warning',
                'summary' => null,
            ],
            default => [
                'status' => 'draft',
                'label' => 'Belum Selesai',
                'status_class' => 'badge-status-warning',
                'summary' => null,
            ],
        };
    }

    public function resolveFormKey(?string $actionUrl, string $title = ''): ?string
    {
        return OnlineFormRegistry::formKeyFromActionUrl($actionUrl)
            ?: OnlineFormRegistry::guessFormKeyFromTitle($title);
    }
}
