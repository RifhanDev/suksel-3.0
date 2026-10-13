<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TenderKakitanganTeknikal extends Model
{
    protected $table = 'tender_kakitangan_teknikals';

    protected $fillable = [
        'uuid',
        'tender_uuid',
        'vendor_id',
        'nama_pegawai',
        'tahap_pendidikan',
        'jumlah_pengalaman',
        'sijil_professional',
        'kategori',
        'sort_order',
    ];

    protected $casts = [
        'jumlah_pengalaman' => 'integer',
        'sort_order'        => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function dokumens(): HasMany
    {
        return $this->hasMany(TenderKakitanganTeknikalDokumen::class, 'kakitangan_uuid', 'uuid');
    }

    public static function calculateKategori(string $tahapPendidikan, ?string $sijilProfessional): string
    {
        $hasSijilProf = ! empty(trim($sijilProfessional ?? ''));

        return match ($tahapPendidikan) {
            'Pascasiswazah'      => 'Kategori A',
            'Diploma dan Ijazah' => 'Kategori B',
            'SPM dan Sijil'      => $hasSijilProf ? 'Kategori B' : 'Kategori C',
            default              => 'Kategori C',
        };
    }

    /**
     * Calculate Bilangan AKM (Anggaran Kelayakan Minimum) targets for Category A, B, and C
     * based on procurement category detail (13=Bangunan, 15=M&E vs Others) and tender value.
     */
    public static function getAkmTargetScores(?int $kategoriDetailId, float $tenderValue): array
    {
        $isBangunanOrME = in_array((int) $kategoriDetailId, [13, 15], true);

        if ($tenderValue < 30000000) {
            return $isBangunanOrME
                ? ['KatA' => 1, 'KatB' => 2, 'KatC' => 3]
                : ['KatA' => 2, 'KatB' => 2, 'KatC' => 3];
        } elseif ($tenderValue >= 30000000 && $tenderValue < 50000000) {
            return $isBangunanOrME
                ? ['KatA' => 1, 'KatB' => 3, 'KatC' => 4]
                : ['KatA' => 2, 'KatB' => 3, 'KatC' => 4];
        } elseif ($tenderValue >= 50000000 && $tenderValue < 100000000) {
            return $isBangunanOrME
                ? ['KatA' => 2, 'KatB' => 3, 'KatC' => 5]
                : ['KatA' => 3, 'KatB' => 4, 'KatC' => 6];
        } else { // >= 100,000,000
            return $isBangunanOrME
                ? ['KatA' => 3, 'KatB' => 5, 'KatC' => 7]
                : ['KatA' => 4, 'KatB' => 7, 'KatC' => 9];
        }
    }
}
