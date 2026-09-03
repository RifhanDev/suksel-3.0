<?php

namespace App\Support;

class OnlineFormRegistry
{
    /**
     * @return array<string, string>
     */
    public static function actionPathToFormKey(): array
    {
        return [
            'penyata-bank' => 'penyata_bank',
            'profil-petender' => 'profil_petender',
            'pengalaman-kerja' => 'pengalaman_kerja',
            'kakitangan-teknikal' => 'kakitangan_teknikal',
            'kerja-dalam-tangan' => 'kerja_dalam_tangan',
            'lembaran-imbangan' => 'lembaran_imbangan',
            'bon-atau-saham' => 'bon_saham',
            'prestasi-kerja-semasa-petender' => 'prestasi_kerja',
        ];
    }

    public static function formKeyFromActionUrl(?string $actionUrl): ?string
    {
        $slug = trim((string) $actionUrl, '/');
        if ($slug === '') {
            return null;
        }

        return self::actionPathToFormKey()[$slug] ?? null;
    }

    public static function guessFormKeyFromTitle(string $title): ?string
    {
        $normalized = strtolower(trim($title));

        return match (true) {
            str_contains($normalized, 'pengalaman kerja') => 'pengalaman_kerja',
            str_contains($normalized, 'kakitangan teknikal') => 'kakitangan_teknikal',
            str_contains($normalized, 'kerja dalam tangan') => 'kerja_dalam_tangan',
            str_contains($normalized, 'profil petender') => 'profil_petender',
            str_contains($normalized, 'penyata bank') => 'penyata_bank',
            str_contains($normalized, 'lembaran imbangan') => 'lembaran_imbangan',
            str_contains($normalized, 'bon') && str_contains($normalized, 'saham') => 'bon_saham',
            str_contains($normalized, 'prestasi kerja') => 'prestasi_kerja',
            default => null,
        };
    }

    public static function label(string $formKey): string
    {
        return match ($formKey) {
            'penyata_bank' => 'Penyata Bank',
            'profil_petender' => 'Profil Petender',
            'pengalaman_kerja' => 'Pengalaman Kerja',
            'kakitangan_teknikal' => 'Senarai Kakitangan Teknikal',
            'kerja_dalam_tangan' => 'Kerja Dalam Tangan',
            'lembaran_imbangan' => 'Lembaran Imbangan',
            'bon_saham' => 'Bon / Saham',
            'prestasi_kerja' => 'Prestasi Kerja',
            default => 'Borang Atas Talian',
        };
    }
}
