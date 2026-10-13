<?php

namespace App\Support;

use App\Models\Ref\RefKaedahPerolehan;
use App\Models\Ref\RefKategoriJenisPerolehan;
use App\Models\Ref\RefLokaliti;
use App\Models\Ref\RefTypeOfContract;
use App\Models\Ref\RefTypeOfPerolehan;
use App\Models\Ref\RefTypeOfTender;
use App\Tender;
use Carbon\Carbon;

class TenderReviewPresenter
{
    public function __construct(protected Tender $tender) {}

    public static function for(Tender $tender): self
    {
        return new self($tender);
    }

    public function tajuk(): string
    {
        return $this->tender->name ?: '-';
    }

    public function tajukSubtitle(): ?string
    {
        $parts = array_filter([
            $this->refName(RefKategoriJenisPerolehan::class, $this->tender->kategori_perolehan_id),
            $this->refName(RefTypeOfPerolehan::class, $this->tender->kategori_perolehan_detail_id),
            $this->refName(RefTypeOfTender::class, $this->tender->jenis_tender_id),
        ]);

        if ($parts === []) {
            $type = Tender::$types[$this->tender->type] ?? null;

            return $type ? '(' . $type . ')' : null;
        }

        return '(' . implode(' — ', $parts) . ')';
    }

    public function kaedahPerolehan(): string
    {
        return $this->refName(RefKaedahPerolehan::class, $this->tender->kaedah_perolehan_id)
            ?? (Tender::$types[$this->tender->type] ?? '-');
    }

    public function kategoriJenisPerolehan(): string
    {
        return $this->refName(RefKategoriJenisPerolehan::class, $this->tender->kategori_perolehan_id) ?? '-';
    }

    public function jenisKontrak(): string
    {
        return $this->refName(RefTypeOfContract::class, $this->tender->jenis_kontrak_id) ?? '-';
    }

    public function ptj(): string
    {
        $name = $this->tender->tenderer?->name;
        if (!$name) {
            return '-';
        }

        $code = $this->tender->tenderer->code ?? $this->tender->tenderer->ref_number ?? null;

        return $code ? strtoupper($name) . ' (' . $code . ')' : strtoupper($name);
    }

    public function noRujukanFail(): string
    {
        return $this->tender->ref_number ?: '-';
    }

    public function noTender(): string
    {
        return $this->tender->no_tender ?: $this->tender->ref_number ?: '-';
    }

    public function tarikhDicipta(): string
    {
        if (empty($this->tender->tarikh_dicipta)) {
            return $this->tender->created_at
                ? Carbon::parse($this->tender->created_at)->format('d/m/Y')
                : '-';
        }

        return Carbon::parse($this->tender->tarikh_dicipta)->format('d/m/Y');
    }

    public function noKontrak(): string
    {
        return $this->tender->no_kontrak ?: '—';
    }

    public function hargaDokumen(): string
    {
        return $this->formatMoney($this->tender->price ?? '0');
    }

    public function hargaIndikatif(): string
    {
        return $this->formatMoney($this->tender->harga_indikatif ?? '0');
    }

    public function anggaranJabatan(): string
    {
        return $this->formatMoney($this->tender->anggaran_jabatan ?? '0');
    }

    public function kategoriPerolehan(): string
    {
        return $this->refName(RefTypeOfPerolehan::class, $this->tender->kategori_perolehan_detail_id) ?? '-';
    }

    public function tempohKontrak(): string
    {
        if (!empty($this->tender->tempoh_kontrak_bulan)) {
            return (int) $this->tender->tempoh_kontrak_bulan . ' Bulan';
        }

        if (!empty($this->tender->tempoh_siap_val)) {
            $unit = match ((string) $this->tender->tempoh_siap_unit) {
                '1' => 'Minggu',
                '2' => 'Bulan',
                default => '',
            };

            return trim($this->tender->tempoh_siap_val . ' ' . $unit) ?: '-';
        }

        return '-';
    }

    public function sumberPeruntukan(): string
    {
        $map = [
            'pembangunan' => 'Pembangunan',
            'mengurus' => 'Mengurus',
            'lain' => 'Lain-lain',
        ];

        $key = strtolower((string) ($this->tender->sumber_peruntukan ?? ''));
        $label = $map[$key] ?? ($this->tender->sumber_peruntukan ? ucfirst($key) : null);

        if ($key === 'lain' && $this->tender->sumber_lain_text) {
            return $label . ' — ' . $this->tender->sumber_lain_text;
        }

        return $label ?? '-';
    }

    public function lokaliti(): string
    {
        return $this->refName(RefLokaliti::class, $this->tender->lokaliti_id) ?? '-';
    }

    public function terbukaKepada(): string
    {
        return match (strtolower((string) ($this->tender->terbuka_kepada ?? ''))) {
            'semua' => 'Semua',
            'bumiputera' => 'Bumiputera',
            default => $this->tender->terbuka_kepada ? ucfirst((string) $this->tender->terbuka_kepada) : '-',
        };
    }

    public function isYes($value): bool
    {
        return (int) $value === 1;
    }

    public function mofGroups(): array
    {
        return array_values($this->tender->mof_code_groups ?? []);
    }

    public function cidbSpecGroups(): array
    {
        return array_values($this->tender->cidb_code_groups ?? []);
    }

    public function cidbGrades(): array
    {
        $grades = $this->tender->relationLoaded('codes')
            ? $this->tender->codes->where('code_type', 'cidb-g')
            : $this->tender->cidb_grades->loadMissing('code');

        return $grades
            ->map(fn($row) => optional($row->code)->label ?? optional($row->code)->code)
            ->filter()
            ->values()
            ->all();
    }

    public function hasMofCodes(): bool
    {
        return count($this->mofGroups()) > 0;
    }

    public function hasCidbCodes(): bool
    {
        return count($this->cidbGrades()) > 0 || count($this->cidbSpecGroups()) > 0;
    }

    public function mofCidbRuleLabel(): string
    {
        return $this->logicLabel($this->tender->mof_cidb_rule);
    }

    public function logicLabel(?string $rule): string
    {
        return strtoupper((string) ($rule ?: 'or')) === 'AND' ? 'DAN' : 'ATAU';
    }

    protected function refName(string $modelClass, $id): ?string
    {
        if (empty($id)) {
            return null;
        }

        return $modelClass::query()->whereKey($id)->value('name');
    }

    protected function formatMoney($value): string
    {
        if ($value === null || $value === '') {
            return '-';
        }

        return number_format((float) $value, 2);
    }
}
