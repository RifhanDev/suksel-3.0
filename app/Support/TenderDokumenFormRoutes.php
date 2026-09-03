<?php

namespace App\Support;

use App\Tender;

class TenderDokumenFormRoutes
{
    /**
     * Resolve a standard checklist action_url to a named route URL for this tender.
     */
    public static function resolve(?string $actionUrl, Tender $tender): ?string
    {
        $slug = trim((string) $actionUrl, '/');
        if ($slug === '' || empty($tender->uuid)) {
            return null;
        }

        $path = '/' . $slug;
        $uuid = $tender->uuid;

        $named = match ($path) {
            '/penyata-bank' => route('penyataBank', ['tenderUuid' => $uuid], false),
            '/lembaran-imbangan' => route('lembaranImbangan', ['tenderUuid' => $uuid], false),
            '/bon-atau-saham' => route('bonAtauSaham', ['tenderUuid' => $uuid], false),
            '/prestasi-kerja-semasa-petender' => route('prestasiKerjaSemasa', ['tenderUuid' => $uuid], false),
            '/pengalaman-kerja' => route('senaraiTeknikal.pengalamanKerja.tender', ['tenderUuid' => $uuid], false),
            '/kakitangan-teknikal' => route('senaraiTeknikal.kakitanganTeknikal.tender', ['tenderUuid' => $uuid], false),
            '/kerja-dalam-tangan' => route('senaraiTeknikal.kerjaDalamTangan', ['tenderUuid' => $uuid], false),
            '/profil-petender' => route('profilPetender', ['tenderUuid' => $uuid], false),
            default => null,
        };

        if ($named !== null) {
            return url($named);
        }

        return url($path . '/' . $uuid);
    }
}
