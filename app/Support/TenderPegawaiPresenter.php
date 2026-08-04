<?php

namespace App\Support;

use App\Services\PenyediaanIklanService;
use App\Tender;
use App\User;

class TenderPegawaiPresenter
{
    public function __construct(
        protected Tender $tender,
        protected array $meta = []
    ) {}

    public static function for(Tender $tender, ?array $meta = null): self
    {
        if ($meta === null) {
            $stored = app(PenyediaanIklanService::class)->getForTender($tender);
            $meta = $stored['meta'] ?? [];
        }

        return new self($tender, $meta);
    }

    public function pegawai1(): array
    {
        $saved = $this->meta['pegawai']['pegawai1'] ?? [];
        if ($this->hasPegawaiData($saved)) {
            return $this->formatPegawai($saved);
        }

        $this->tender->loadMissing('creator.organizationunit');

        return $this->formatPegawaiFromUser($this->tender->creator);
    }

    public function pegawai2(): ?array
    {
        $saved = $this->meta['pegawai']['pegawai2'] ?? [];
        if ($this->hasPegawaiData($saved)) {
            return $this->formatPegawai($saved);
        }

        $this->tender->loadMissing('officer.organizationunit');

        if ($this->tender->officer) {
            return $this->formatPegawaiFromUser($this->tender->officer);
        }

        return null;
    }

    public function hasPegawai2(): bool
    {
        return $this->pegawai2() !== null;
    }

    protected function hasPegawaiData(array $data): bool
    {
        foreach (['nama', 'emel', 'tel', 'jabatan'] as $field) {
            $value = trim((string) ($data[$field] ?? ''));
            if ($value !== '') {
                return true;
            }
        }

        return ! empty($data['user_id']);
    }

    protected function formatPegawai(array $data): array
    {
        $nama = trim((string) ($data['nama'] ?? ''));
        if ($nama !== '' && ctype_digit($nama)) {
            $user = User::query()->with('organizationunit')->find((int) $nama);
            if ($user) {
                return $this->formatPegawaiFromUser($user);
            }
        }

        return [
            'nama' => $nama !== '' ? $nama : '-',
            'emel' => $this->displayValue($data['emel'] ?? null),
            'tel' => $this->displayValue($data['tel'] ?? null),
            'jabatan' => $this->displayValue($data['jabatan'] ?? null),
        ];
    }

    protected function formatPegawaiFromUser(?User $user): array
    {
        $fields = app(PenyediaanIklanService::class)->pegawaiFieldsFromUser($user);

        return [
            'nama' => $fields['nama'] !== '' ? $fields['nama'] : '-',
            'emel' => $fields['emel'] !== '' ? $fields['emel'] : '-',
            'tel' => $fields['tel'] !== '' ? $fields['tel'] : '-',
            'jabatan' => $fields['jabatan'] !== '' ? $fields['jabatan'] : '-',
        ];
    }

    protected function displayValue($value): string
    {
        $value = trim((string) ($value ?? ''));

        return $value !== '' ? $value : '-';
    }
}
