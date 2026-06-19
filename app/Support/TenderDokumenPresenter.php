<?php

namespace App\Support;

use App\Models\FinancialChecklistItem;
use App\Models\KewanganKerjaItem;
use App\Models\TechnicalChecklistItem;
use App\Tender;
use Illuminate\Support\Collection;

class TenderDokumenPresenter
{
    public function __construct(protected Tender $tender) {}

    public static function for(Tender $tender): self
    {
        return new self($tender);
    }

    /**
     * @return array<int, array{nama: string, tindakan: string, badge_class: string, source: string}>
     */
    public function items(): array
    {
        return collect()
            ->concat($this->technicalItems())
            ->concat($this->financialItems())
            ->concat($this->kewanganKerjaItems())
            ->values()
            ->all();
    }

    protected function technicalItems(): Collection
    {
        return TechnicalChecklistItem::query()
            ->whereHas('header', fn ($query) => $query->where('tender_id', $this->tender->id))
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (TechnicalChecklistItem $item) => $this->formatItem($item, 'technical'))
            ->toBase();
    }

    protected function financialItems(): Collection
    {
        return FinancialChecklistItem::query()
            ->whereHas('header', fn ($query) => $query->where('tender_id', $this->tender->id))
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (FinancialChecklistItem $item) => $this->formatItem($item, 'financial'))
            ->toBase();
    }

    protected function kewanganKerjaItems(): Collection
    {
        return KewanganKerjaItem::query()
            ->whereHas('header', fn ($query) => $query->where('tender_id', $this->tender->id))
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (KewanganKerjaItem $item) => $this->formatItem($item, 'kewangan_kerja'))
            ->toBase();
    }

    /**
     * @return array{nama: string, tindakan: string, badge_class: string, source: string}
     */
    protected function formatItem(TechnicalChecklistItem|FinancialChecklistItem|KewanganKerjaItem $item, string $source): array
    {
        $tindakan = VendorActionLabel::resolve(
            $item->source_type,
            $item->mechanism,
            $item->vendor_action
        );

        return [
            'nama' => $item->title,
            'tindakan' => $tindakan,
            'badge_class' => VendorActionLabel::badgeClass($tindakan),
            'source' => $source,
        ];
    }
}
