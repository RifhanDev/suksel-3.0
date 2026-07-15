<?php

namespace App\Services;

use App\Support\VendorCidbMeta;
use App\Support\VendorCidbMetaDiff;
use App\Vendor;
use Carbon\Carbon;

class VendorCidbSyncService
{
    /**
     * @return array{change_count: int, has_changes: bool}
     */
    public function integrate(Vendor $vendor, ?int $userId = null): array
    {
        $meta = VendorCidbMeta::normalizeMeta(is_array($vendor->meta) ? $vendor->meta : null);
        $now = Carbon::now()->toIso8601String();

        $previousSections = VendorCidbMeta::currentSections($meta);
        $previousSyncedAt = VendorCidbMeta::currentSyncedAt($meta);

        $fetchedSections = $this->fetchMockFromApi($vendor, $previousSections);

        $source1 = VendorCidbMeta::buildSnapshot($previousSections, 'Sebelum', $previousSyncedAt);
        $source2 = VendorCidbMeta::buildSnapshot($fetchedSections, 'Selepas', $now);
        $diff = VendorCidbMetaDiff::compare($previousSections, $fetchedSections);

        $vendor->meta = [
            'source' => 'cidb',
            'synced_at' => $now,
            'synced_by' => $userId,
            'source1' => $source1,
            'source2' => $source2,
            'diff' => $diff,
        ];

        $vendor->save();

        return [
            'change_count' => $diff['change_count'],
            'has_changes' => $diff['has_changes'],
        ];
    }

    /**
     * @param  array<string, mixed>  $currentSections
     * @return array<string, mixed>
     */
    private function fetchMockFromApi(Vendor $vendor, array $currentSections): array
    {
        if ($currentSections === []) {
            $sections = VendorCidbMeta::dummySections();

            return $this->personalizeSections($sections, $vendor);
        }

        $sections = json_decode(json_encode($currentSections), true);

        return $this->applyMockChanges($sections, $vendor);
    }

    /**
     * @param  array<string, mixed>  $sections
     * @return array<string, mixed>
     */
    private function applyMockChanges(array $sections, Vendor $vendor): array
    {
        $score = $sections[VendorCidbMeta::SECTION_PERIHAL_SCORE_MCORE] ?? [];
        $score['nilai_skor'] = (int) ($score['nilai_skor'] ?? 78) + 4;
        $score['status'] = 'Layak';
        $score['tarikh_penilaian'] = Carbon::now()->toDateString();
        $score['catatan'] = 'Kemaskini automatik dari integrasi CIDB (mock)';
        $sections[VendorCidbMeta::SECTION_PERIHAL_SCORE_MCORE] = $score;

        $registration = $sections[VendorCidbMeta::SECTION_MAKLUMAT_PENDAFTARAN_CIDB] ?? [];
        $registration['gred_utama'] = 'G6';
        $registration['tarikh_tamat_sijil'] = Carbon::now()->addYear()->toDateString();
        $registration['status_pendaftaran'] = 'Aktif';
        $sections[VendorCidbMeta::SECTION_MAKLUMAT_PENDAFTARAN_CIDB] = $registration;

        $directors = $sections[VendorCidbMeta::SECTION_PENGARAH] ?? [];
        $directors[] = [
            'nama' => 'Zulkifli bin Omar',
            'no_kad_pengenalan' => '850606-10-4321',
            'jawatan' => 'PENGARAH',
            'warganegara' => 'MALAYSIA',
            'status_bumiputera' => 'Bumiputera',
        ];
        $sections[VendorCidbMeta::SECTION_PENGARAH] = $directors;

        $projects = $sections[VendorCidbMeta::SECTION_MAKLUMAT_PROJEK] ?? [];
        if (isset($projects[1])) {
            $projects[1]['status'] = 'Siap';
            $projects[1]['tarikh_siap'] = Carbon::now()->subMonth()->toDateString();
        }
        $projects[] = [
            'nama_projek' => 'Pembinaan Kompleks Sukan Daerah',
            'pelanggan' => 'Majlis Perbandaran Petaling Jaya',
            'nilai_kontrak' => 6200000.00,
            'tarikh_mula' => Carbon::now()->subMonths(6)->toDateString(),
            'tarikh_siap' => Carbon::now()->addMonths(6)->toDateString(),
            'status' => 'Dalam Pelaksanaan',
        ];
        $sections[VendorCidbMeta::SECTION_MAKLUMAT_PROJEK] = $projects;

        $complaints = $sections[VendorCidbMeta::SECTION_ADUAN] ?? [];
        $complaints[] = [
            'no_aduan' => 'ADU-'.Carbon::now()->format('Y').'-'.str_pad((string) (count($complaints) + 1), 3, '0', STR_PAD_LEFT),
            'tarikh_aduan' => Carbon::now()->toDateString(),
            'perihal' => 'Kelewatan penyiapan kerja kum-kum',
            'status' => 'Dalam Siasatan',
        ];
        $sections[VendorCidbMeta::SECTION_ADUAN] = $complaints;

        return $this->personalizeSections($sections, $vendor);
    }

    /**
     * @param  array<string, mixed>  $sections
     * @return array<string, mixed>
     */
    private function personalizeSections(array $sections, Vendor $vendor): array
    {
        if ($vendor->name) {
            $sections[VendorCidbMeta::SECTION_MAKLUMAT_SYARIKAT]['nama_syarikat'] = $vendor->name;
        }

        if ($vendor->registration) {
            $sections[VendorCidbMeta::SECTION_MAKLUMAT_SYARIKAT]['no_pendaftaran_ssm'] = $vendor->registration;
        }

        if ($vendor->address) {
            $sections[VendorCidbMeta::SECTION_MAKLUMAT_SYARIKAT]['alamat_berdaftar'] = $vendor->address;
        }

        if ($vendor->cidb_ref_no) {
            $sections[VendorCidbMeta::SECTION_MAKLUMAT_PENDAFTARAN_CIDB]['no_pendaftaran'] = $vendor->cidb_ref_no;
        }

        $sections[VendorCidbMeta::SECTION_KOMPOSISI_BUMIPUTERA]['peratus_bumiputera'] =
            (float) ($vendor->bumi_percentage ?? $sections[VendorCidbMeta::SECTION_KOMPOSISI_BUMIPUTERA]['peratus_bumiputera'] ?? 0);

        $sections[VendorCidbMeta::SECTION_KOMPOSISI_BUMIPUTERA]['peratus_bukan_bumiputera'] =
            (float) ($vendor->nonbumi_percentage ?? $sections[VendorCidbMeta::SECTION_KOMPOSISI_BUMIPUTERA]['peratus_bukan_bumiputera'] ?? 0);

        $sections[VendorCidbMeta::SECTION_KOMPOSISI_BUMIPUTERA]['peratus_asing'] =
            (float) ($vendor->foreigner_percentage ?? $sections[VendorCidbMeta::SECTION_KOMPOSISI_BUMIPUTERA]['peratus_asing'] ?? 0);

        return $sections;
    }
}
