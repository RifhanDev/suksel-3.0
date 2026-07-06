<?php

namespace App\Support;

class VendorCidbMetaDiff
{
    /**
     * @var array<string, string>
     */
    private const LIST_IDENTITY_KEYS = [
        VendorCidbMeta::SECTION_PENGARAH => 'no_kad_pengenalan',
        VendorCidbMeta::SECTION_PEMEGANG_SAHAM => 'no_kad_pengenalan',
        VendorCidbMeta::SECTION_PENAMA_SPKK => 'no_kad_pengenalan',
        VendorCidbMeta::SECTION_MAKLUMAT_PROJEK => 'nama_projek',
        VendorCidbMeta::SECTION_MAKLUMAT_PERMOHONAN_KEBENARAN_KHAS => 'no_rujukan',
        VendorCidbMeta::SECTION_TINDAKAN_KE_ATAS_KONTRAKTOR => 'no_rujukan',
        VendorCidbMeta::SECTION_ADUAN => 'no_aduan',
        VendorCidbMeta::SECTION_TINDAKAN_TATATERTIB => 'no_rujukan',
    ];

    /**
     * @param  array<string, mixed>  $beforeSections
     * @param  array<string, mixed>  $afterSections
     * @return array{has_changes: bool, change_count: int, rows: list<array<string, mixed>>}
     */
    public static function compare(array $beforeSections, array $afterSections): array
    {
        $rows = [];

        foreach (VendorCidbMeta::sectionLabels() as $sectionKey => $sectionLabel) {
            $before = $beforeSections[$sectionKey] ?? (VendorCidbMeta::isListSection($sectionKey) ? [] : []);
            $after = $afterSections[$sectionKey] ?? (VendorCidbMeta::isListSection($sectionKey) ? [] : []);

            if (VendorCidbMeta::isListSection($sectionKey)) {
                $rows = array_merge(
                    $rows,
                    self::diffListSection($sectionKey, $sectionLabel, $before, $after)
                );
                continue;
            }

            $rows = array_merge(
                $rows,
                self::diffScalarSection($sectionKey, $sectionLabel, $before, $after)
            );
        }

        return [
            'has_changes' => count($rows) > 0,
            'change_count' => count($rows),
            'rows' => $rows,
        ];
    }

    /**
     * @param  array<string, mixed>  $before
     * @param  array<string, mixed>  $after
     * @return list<array<string, mixed>>
     */
    private static function diffScalarSection(string $sectionKey, string $sectionLabel, array $before, array $after): array
    {
        $rows = [];
        $fields = array_unique(array_merge(array_keys($before), array_keys($after)));

        foreach ($fields as $field) {
            $old = $before[$field] ?? null;
            $new = $after[$field] ?? null;

            if (self::valuesEqual($old, $new)) {
                continue;
            }

            $rows[] = [
                'section_key' => $sectionKey,
                'section_label' => $sectionLabel,
                'field' => $field,
                'field_label' => VendorCidbMeta::humanizeKey($field),
                'type' => self::changeType($old, $new),
                'old' => $old,
                'new' => $new,
                'record_label' => null,
            ];
        }

        return $rows;
    }

    /**
     * @param  list<array<string, mixed>>  $before
     * @param  list<array<string, mixed>>  $after
     * @return list<array<string, mixed>>
     */
    private static function diffListSection(string $sectionKey, string $sectionLabel, array $before, array $after): array
    {
        $rows = [];
        $identityKey = self::LIST_IDENTITY_KEYS[$sectionKey] ?? 'nama';
        $beforeMap = self::indexList($before, $identityKey);
        $afterMap = self::indexList($after, $identityKey);

        foreach ($afterMap as $identity => $afterItem) {
            if (! isset($beforeMap[$identity])) {
                foreach ($afterItem as $field => $value) {
                    $rows[] = self::listRow(
                        $sectionKey,
                        $sectionLabel,
                        $field,
                        $afterItem,
                        'added',
                        null,
                        $value,
                        $identityKey,
                        $identity
                    );
                }
                continue;
            }

            foreach ($afterItem as $field => $value) {
                $old = $beforeMap[$identity][$field] ?? null;
                if (self::valuesEqual($old, $value)) {
                    continue;
                }

                $rows[] = self::listRow(
                    $sectionKey,
                    $sectionLabel,
                    $field,
                    $afterItem,
                    'modified',
                    $old,
                    $value,
                    $identityKey,
                    $identity
                );
            }
        }

        foreach ($beforeMap as $identity => $beforeItem) {
            if (isset($afterMap[$identity])) {
                continue;
            }

            foreach ($beforeItem as $field => $value) {
                $rows[] = self::listRow(
                    $sectionKey,
                    $sectionLabel,
                    $field,
                    $beforeItem,
                    'removed',
                    $value,
                    null,
                    $identityKey,
                    $identity
                );
            }
        }

        return $rows;
    }

    /**
     * @param  list<array<string, mixed>>  $items
     * @return array<string, array<string, mixed>>
     */
    private static function indexList(array $items, string $identityKey): array
    {
        $map = [];

        foreach ($items as $index => $item) {
            if (! is_array($item)) {
                continue;
            }

            $identity = (string) ($item[$identityKey] ?? ('rekod-'.$index));
            $map[$identity] = $item;
        }

        return $map;
    }

    /**
     * @param  array<string, mixed>  $record
     * @return array<string, mixed>
     */
    private static function listRow(
        string $sectionKey,
        string $sectionLabel,
        string $field,
        array $record,
        string $type,
        mixed $old,
        mixed $new,
        string $identityKey,
        string $identity
    ): array {
        $recordLabel = (string) ($record[$identityKey] ?? $record['nama'] ?? $identity);

        return [
            'section_key' => $sectionKey,
            'section_label' => $sectionLabel,
            'field' => $field,
            'field_label' => VendorCidbMeta::humanizeKey($field),
            'type' => $type,
            'old' => $old,
            'new' => $new,
            'record_label' => $recordLabel,
        ];
    }

    private static function changeType(mixed $old, mixed $new): string
    {
        if (self::isEmpty($old) && ! self::isEmpty($new)) {
            return 'added';
        }

        if (! self::isEmpty($old) && self::isEmpty($new)) {
            return 'removed';
        }

        return 'modified';
    }

    private static function isEmpty(mixed $value): bool
    {
        return $value === null || $value === '';
    }

    private static function valuesEqual(mixed $old, mixed $new): bool
    {
        if (is_numeric($old) && is_numeric($new)) {
            return (float) $old === (float) $new;
        }

        return $old === $new;
    }
}
