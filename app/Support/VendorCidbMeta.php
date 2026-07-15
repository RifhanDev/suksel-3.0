<?php

namespace App\Support;

use Illuminate\Support\Str;

class VendorCidbMeta
{
    public const SECTION_MAKLUMAT_PENDAFTARAN_CIDB = 'maklumat_pendaftaran_cidb';

    public const SECTION_MAKLUMAT_SYARIKAT = 'maklumat_syarikat';

    public const SECTION_PENGARAH = 'pengarah';

    public const SECTION_PEMEGANG_SAHAM = 'pemegang_saham';

    public const SECTION_PERIHAL_SCORE_MCORE = 'perihal_score_mcore';

    public const SECTION_PENAMA_SPKK = 'penama_spkk';

    public const SECTION_KOMPOSISI_BUMIPUTERA = 'komposisi_bumiputera';

    public const SECTION_MAKLUMAT_PROJEK = 'maklumat_projek';

    public const SECTION_MAKLUMAT_PERMOHONAN_KEBENARAN_KHAS = 'maklumat_permohonan_kebenaran_khas';

    public const SECTION_TINDAKAN_KE_ATAS_KONTRAKTOR = 'tindakan_ke_atas_kontraktor';

    public const SECTION_ADUAN = 'aduan';

    public const SECTION_TINDAKAN_TATATERTIB = 'tindakan_tatatertib';

    /**
     * @return array<string, string>
     */
    public static function sectionLabels(): array
    {
        return [
            self::SECTION_MAKLUMAT_PENDAFTARAN_CIDB => 'Maklumat Pendaftaran CIDB',
            self::SECTION_MAKLUMAT_SYARIKAT => 'Maklumat Syarikat/ Perniagaan/ Koperasi/ Pertubuhan',
            self::SECTION_PENGARAH => 'Pengarah / Pemilik / Ahli Lembaga Pengarah',
            self::SECTION_PEMEGANG_SAHAM => 'Pemegang saham',
            self::SECTION_PERIHAL_SCORE_MCORE => 'Perihal SCORE / MCORE',
            self::SECTION_PENAMA_SPKK => 'Penama SPKK',
            self::SECTION_KOMPOSISI_BUMIPUTERA => 'Komposisi Bumiputera',
            self::SECTION_MAKLUMAT_PROJEK => 'Maklumat Projek',
            self::SECTION_MAKLUMAT_PERMOHONAN_KEBENARAN_KHAS => 'Maklumat Permohonan Kebenaran Khas Menyertai tender/ sebut harga/ kerja undi/ pra kelayakan',
            self::SECTION_TINDAKAN_KE_ATAS_KONTRAKTOR => 'Tindakan Ke atas Kontraktor',
            self::SECTION_ADUAN => 'Aduan',
            self::SECTION_TINDAKAN_TATATERTIB => 'Tindakan Tatatertib (PPK,SPKK,STB,SPEK)',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function empty(): array
    {
        return [
            'source' => 'cidb',
            'synced_at' => null,
            'sections' => [
                self::SECTION_MAKLUMAT_PENDAFTARAN_CIDB => [
                    'no_pendaftaran' => null,
                    'tarikh_pendaftaran' => null,
                    'status_pendaftaran' => null,
                    'gred_utama' => null,
                    'tarikh_tamat_sijil' => null,
                ],
                self::SECTION_MAKLUMAT_SYARIKAT => [
                    'nama_syarikat' => null,
                    'jenis_entiti' => null,
                    'no_pendaftaran_ssm' => null,
                    'alamat_berdaftar' => null,
                    'negeri' => null,
                    'poskod' => null,
                    'telefon' => null,
                    'emel' => null,
                ],
                self::SECTION_PENGARAH => [],
                self::SECTION_PEMEGANG_SAHAM => [],
                self::SECTION_PERIHAL_SCORE_MCORE => [
                    'jenis_skor' => null,
                    'nilai_skor' => null,
                    'tarikh_penilaian' => null,
                    'status' => null,
                ],
                self::SECTION_PENAMA_SPKK => [],
                self::SECTION_KOMPOSISI_BUMIPUTERA => [
                    'peratus_bumiputera' => null,
                    'peratus_bukan_bumiputera' => null,
                    'peratus_asing' => null,
                    'status_bumiputera' => null,
                ],
                self::SECTION_MAKLUMAT_PROJEK => [],
                self::SECTION_MAKLUMAT_PERMOHONAN_KEBENARAN_KHAS => [],
                self::SECTION_TINDAKAN_KE_ATAS_KONTRAKTOR => [],
                self::SECTION_ADUAN => [],
                self::SECTION_TINDAKAN_TATATERTIB => [],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function emptySections(): array
    {
        return self::empty()['sections'];
    }

    /**
     * @return array{label: string, synced_at: string|null, sections: array<string, mixed>}
     */
    public static function buildSnapshot(array $sections, string $label, ?string $syncedAt = null): array
    {
        return [
            'label' => $label,
            'synced_at' => $syncedAt,
            'sections' => $sections,
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function normalizeMeta(?array $meta): ?array
    {
        if (! is_array($meta)) {
            return null;
        }

        if (isset($meta['source2']['sections']) && is_array($meta['source2']['sections'])) {
            return $meta;
        }

        if (! isset($meta['sections']) || ! is_array($meta['sections'])) {
            return $meta;
        }

        $syncedAt = $meta['synced_at'] ?? null;

        return [
            'source' => $meta['source'] ?? 'cidb',
            'synced_at' => $syncedAt,
            'synced_by' => $meta['synced_by'] ?? null,
            'source1' => self::buildSnapshot(self::emptySections(), 'Sebelum', null),
            'source2' => self::buildSnapshot($meta['sections'], 'Selepas', $syncedAt),
            'diff' => [
                'has_changes' => false,
                'change_count' => 0,
                'rows' => [],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function currentSections(?array $meta): array
    {
        $meta = self::normalizeMeta($meta);

        if (! is_array($meta)) {
            return [];
        }

        if (isset($meta['source2']['sections']) && is_array($meta['source2']['sections'])) {
            return $meta['source2']['sections'];
        }

        if (isset($meta['sections']) && is_array($meta['sections'])) {
            return $meta['sections'];
        }

        return [];
    }

    public static function currentSyncedAt(?array $meta): ?string
    {
        $meta = self::normalizeMeta($meta);

        if (! is_array($meta)) {
            return null;
        }

        return $meta['source2']['synced_at'] ?? $meta['synced_at'] ?? null;
    }

    /**
     * @return array<string, mixed>
     */
    public static function dummySections(): array
    {
        return [
                self::SECTION_MAKLUMAT_PENDAFTARAN_CIDB => [
                    'no_pendaftaran' => 'CIDB-2024-001234',
                    'tarikh_pendaftaran' => '2024-01-15',
                    'status_pendaftaran' => 'Aktif',
                    'gred_utama' => 'G7',
                    'tarikh_tamat_sijil' => '2027-01-14',
                    'kategori' => 'Bumiputera',
                    'negeri_daftar' => 'Selangor',
                ],
                self::SECTION_MAKLUMAT_SYARIKAT => [
                    'nama_syarikat' => 'Syarikat Pembinaan Maju Sdn Bhd',
                    'jenis_entiti' => 'ROC: SENDIRIAN BERHAD',
                    'no_pendaftaran_ssm' => '202301012345',
                    'alamat_berdaftar' => 'No. 12, Jalan Teknologi 3/1, Taman Sains Selangor',
                    'negeri' => 'Selangor',
                    'poskod' => '47810',
                    'telefon' => '03-12345678',
                    'emel' => 'info@majubina.com.my',
                    'laman_web' => 'https://www.majubina.com.my',
                ],
                self::SECTION_PENGARAH => [
                    [
                        'nama' => 'Ahmad bin Abdullah',
                        'no_kad_pengenalan' => '800101-14-5678',
                        'jawatan' => 'PENGARAH URUSAN',
                        'warganegara' => 'MALAYSIA',
                        'status_bumiputera' => 'Bumiputera',
                    ],
                    [
                        'nama' => 'Siti Aminah binti Hassan',
                        'no_kad_pengenalan' => '820505-10-1234',
                        'jawatan' => 'PENGARAH',
                        'warganegara' => 'MALAYSIA',
                        'status_bumiputera' => 'Bumiputera',
                    ],
                ],
                self::SECTION_PEMEGANG_SAHAM => [
                    [
                        'nama' => 'Ahmad bin Abdullah',
                        'no_kad_pengenalan' => '800101-14-5678',
                        'peratus_saham' => 60.00,
                        'status_bumiputera' => 'Bumiputera',
                    ],
                    [
                        'nama' => 'Siti Aminah binti Hassan',
                        'no_kad_pengenalan' => '820505-10-1234',
                        'peratus_saham' => 40.00,
                        'status_bumiputera' => 'Bumiputera',
                    ],
                ],
                self::SECTION_PERIHAL_SCORE_MCORE => [
                    'jenis_skor' => 'SCORE',
                    'nilai_skor' => 78,
                    'tarikh_penilaian' => '2025-11-20',
                    'status' => 'Layak',
                    'catatan' => 'Penilaian tahunan CIDB',
                ],
                self::SECTION_PENAMA_SPKK => [
                    [
                        'nama' => 'Mohd Faizal bin Ismail',
                        'no_kad_pengenalan' => '790303-08-9012',
                        'no_sijil_spkk' => 'SPKK-2023-4567',
                        'tarikh_tamat' => '2026-12-31',
                        'gred' => 'G7',
                    ],
                ],
                self::SECTION_KOMPOSISI_BUMIPUTERA => [
                    'peratus_bumiputera' => 100.00,
                    'peratus_bukan_bumiputera' => 0.00,
                    'peratus_asing' => 0.00,
                    'status_bumiputera' => 'Bumiputera',
                ],
                self::SECTION_MAKLUMAT_PROJEK => [
                    [
                        'nama_projek' => 'Pembinaan Bangunan Pejabat Kerajaan Negeri',
                        'pelanggan' => 'Kerajaan Negeri Selangor',
                        'nilai_kontrak' => 15000000.00,
                        'tarikh_mula' => '2023-03-01',
                        'tarikh_siap' => '2025-06-30',
                        'status' => 'Siap',
                    ],
                    [
                        'nama_projek' => 'Kerja Naik Taraf Jalan Utama',
                        'pelanggan' => 'Jabatan Kerja Raya',
                        'nilai_kontrak' => 8500000.00,
                        'tarikh_mula' => '2024-01-10',
                        'tarikh_siap' => '2025-12-31',
                        'status' => 'Dalam Pelaksanaan',
                    ],
                ],
                self::SECTION_MAKLUMAT_PERMOHONAN_KEBENARAN_KHAS => [
                    [
                        'no_rujukan' => 'KK-2025-001',
                        'jenis_permohonan' => 'Tender',
                        'nama_tender' => 'Pembinaan Sekolah Menengah',
                        'tarikh_permohonan' => '2025-02-10',
                        'status' => 'Diluluskan',
                    ],
                ],
                self::SECTION_TINDAKAN_KE_ATAS_KONTRAKTOR => [
                    [
                        'no_rujukan' => 'TK-2024-089',
                        'jenis_tindakan' => 'Amaran Bertulis',
                        'tarikh_tindakan' => '2024-08-15',
                        'sebab' => 'Kelewatan penyerahan dokumen',
                        'status' => 'Selesai',
                    ],
                ],
                self::SECTION_ADUAN => [
                    [
                        'no_aduan' => 'ADU-2025-012',
                        'tarikh_aduan' => '2025-01-20',
                        'perihal' => 'Kualiti kerja tidak memuaskan di tapak binaan',
                        'status' => 'Ditutup',
                    ],
                ],
                self::SECTION_TINDAKAN_TATATERTIB => [
                    [
                        'no_rujukan' => 'TT-PPK-2023-045',
                        'jenis' => 'PPK',
                        'tarikh_tindakan' => '2023-11-05',
                        'perihal' => 'Kesalahan prosedur keselamatan tapak binaan',
                        'hukuman' => 'Denda RM5,000',
                        'status' => 'Selesai',
                    ],
                ],
            ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function dummy(): array
    {
        $syncedAt = '2026-07-03T02:00:00+08:00';
        $sections = self::dummySections();

        return [
            'source' => 'cidb',
            'synced_at' => $syncedAt,
            'synced_by' => null,
            'source1' => self::buildSnapshot(self::emptySections(), 'Sebelum', null),
            'source2' => self::buildSnapshot($sections, 'Selepas', $syncedAt),
            'diff' => [
                'has_changes' => false,
                'change_count' => 0,
                'rows' => [],
            ],
        ];
    }

    /**
     * @return list<array{key: string, label: string, data: mixed}>
     */
    public static function resolveSections(?array $meta): array
    {
        $meta = self::normalizeMeta($meta);
        $storedSections = self::currentSections($meta);
        $sections = [];

        foreach (self::sectionLabels() as $key => $label) {
            $sections[] = [
                'key' => $key,
                'label' => $label,
                'data' => $storedSections[$key] ?? (self::isListSection($key) ? [] : []),
            ];
        }

        return $sections;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function diffRows(?array $meta): array
    {
        $meta = self::normalizeMeta($meta);

        if (! is_array($meta) || ! isset($meta['diff']['rows']) || ! is_array($meta['diff']['rows'])) {
            return [];
        }

        return $meta['diff']['rows'];
    }

    public static function hasDiff(?array $meta): bool
    {
        $meta = self::normalizeMeta($meta);

        return (bool) ($meta['diff']['has_changes'] ?? false);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function groupDiffRowsBySection(array $rows): array
    {
        $grouped = [];

        foreach ($rows as $row) {
            $sectionKey = $row['section_key'] ?? 'unknown';

            if (! isset($grouped[$sectionKey])) {
                $grouped[$sectionKey] = [
                    'section_key' => $sectionKey,
                    'section_label' => $row['section_label'] ?? self::humanizeKey($sectionKey),
                    'rows' => [],
                ];
            }

            $grouped[$sectionKey]['rows'][] = $row;
        }

        return array_values($grouped);
    }

    public static function changeTypeLabel(string $type): string
    {
        return match ($type) {
            'added' => 'Ditambah',
            'removed' => 'Dibuang',
            default => 'Dikemaskini',
        };
    }

    public static function changeTypeBadgeClass(string $type): string
    {
        return match ($type) {
            'added' => 'bg-success-subtle text-success border-success-subtle',
            'removed' => 'bg-danger-subtle text-danger border-danger-subtle',
            default => 'bg-warning-subtle text-warning border-warning-subtle',
        };
    }

    /**
     * @return list<string>
     */
    public static function listSectionKeys(): array
    {
        return [
            self::SECTION_PENGARAH,
            self::SECTION_PEMEGANG_SAHAM,
            self::SECTION_PENAMA_SPKK,
            self::SECTION_MAKLUMAT_PROJEK,
            self::SECTION_MAKLUMAT_PERMOHONAN_KEBENARAN_KHAS,
            self::SECTION_TINDAKAN_KE_ATAS_KONTRAKTOR,
            self::SECTION_ADUAN,
            self::SECTION_TINDAKAN_TATATERTIB,
        ];
    }

    public static function isListSection(string $key): bool
    {
        return in_array($key, self::listSectionKeys(), true);
    }

    public static function humanizeKey(string $key): string
    {
        return ucwords(str_replace('_', ' ', $key));
    }

    public static function formatDisplayValue(mixed $value): string
    {
        if ($value === null || $value === '') {
            return '-';
        }

        if (is_bool($value)) {
            return $value ? 'Ya' : 'Tidak';
        }

        if (is_int($value) || is_float($value)) {
            return is_float($value)
                ? number_format($value, 2)
                : (string) $value;
        }

        if (is_numeric($value) && is_string($value) && str_contains($value, '.')) {
            return number_format((float) $value, 2);
        }

        return (string) $value;
    }

    public static function sectionHasData(mixed $data): bool
    {
        if (! is_array($data)) {
            return $data !== null && $data !== '';
        }

        if ($data === []) {
            return false;
        }

        if (array_is_list($data)) {
            return count($data) > 0;
        }

        foreach ($data as $value) {
            if ($value !== null && $value !== '') {
                return true;
            }
        }

        return false;
    }

    public static function sectionSearchText(string $label, mixed $data): string
    {
        return Str::lower(trim($label.' '.json_encode($data, JSON_UNESCAPED_UNICODE)));
    }
}
