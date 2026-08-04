<?php

namespace App\Support;

/**
 * Checklist item mechanism slugs. `mechanism` comes from the STOS backend; specification
 * rows are identified separately via `source_type = specification_document`.
 */
final class ChecklistMechanism
{
    public const PETENDER_MUAT_NAIK = 'petender_muat_naik';

    public const PTJ_MUAT_NAIK = 'ptj_muat_naik';

    public const BORANG_ATAS_TALIAN = 'borang_atas_talian';

    /** source_type marking a checklist item as a specification row. */
    public const SOURCE_SPECIFICATION = 'specification_document';

    /** Display label for the "Mekanisma" column. */
    public static function label(?string $mechanism, ?string $sourceType = null): string
    {
        if ($sourceType === self::SOURCE_SPECIFICATION) {
            return 'Spesifikasi';
        }

        return match ($mechanism) {
            self::PETENDER_MUAT_NAIK => 'Petender Muat Naik',
            self::PTJ_MUAT_NAIK      => 'PTJ Muat Naik',
            self::BORANG_ATAS_TALIAN => 'Borang Atas Talian',
            default                  => '-',
        };
    }
}
