<?php

namespace App\Models;

use App\Tender;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Jawatankuasa extends Model
{
    public const PERANAN_PENGERUSI = '1';
    public const PERANAN_SETIAUSAHA = '2';
    public const PERANAN_AHLI = '3';

    /**
     * Minimum appointments required per committee:
     * - spec: 1 Pengerusi + 2 Ahli
     * - open: 1 Pengerusi + 1 Ahli
     * - tech: 1 Pengerusi + 2 Ahli
     * - fin:  1 Pengerusi + 2 Ahli
     * - eval: 1 Pengerusi + 2 Ahli
     * Setiausaha is optional for all committees.
     *
     * @return array<string, array{pengerusi: int, ahli: int}>
     */
    public static function minimumRequirements(): array
    {
        return [
            'spec' => ['pengerusi' => 1, 'ahli' => 2],
            'open' => ['pengerusi' => 1, 'ahli' => 1],
            'tech' => ['pengerusi' => 1, 'ahli' => 2],
            'fin'  => ['pengerusi' => 1, 'ahli' => 2],
            'eval' => ['pengerusi' => 1, 'ahli' => 2],
        ];
    }

    /**
     * Required committee types based on tender peringkat.
     * 1 Peringkat: spec, open, eval
     * 2 Peringkat: spec, open, tech, fin
     *
     * @return array<int, string>
     */
    public static function requiredJenisForPeringkat(int $peringkat): array
    {
        return $peringkat === 1
            ? ['spec', 'open', 'eval']
            : ['spec', 'open', 'tech', 'fin'];
    }

    /**
     * Determine if a committee tab satisfies the minimum appointment requirement:
     * - Must have at least minPengerusi (1 Pengerusi).
     * - Must have at least minAhli (2 Ahli for spec/tech/fin/eval; 1 Ahli for open).
     * Setiausaha is optional for all committee types and does not substitute for an Ahli.
     */
    public static function satisfiesRequirement(string $jenis, int $pengerusiCount, int $ahliCount): bool
    {
        $min = self::minimumRequirements()[$jenis] ?? ['pengerusi' => 1, 'ahli' => 2];

        return $pengerusiCount >= $min['pengerusi'] && $ahliCount >= $min['ahli'];
    }

    protected $table = 'jawatankuasas';

    protected $fillable = [
        'tender_id',
        'jenis_jawatankuasa',
        'p_p',
        'peranan',
        'user_id',
        'catatan',
        'tarikh_mesyuarat',
        'masa_mesyuarat',
        'lokasi_mesyuarat',
        'dokumen_sokongan_nama',
        'dokumen_sokongan_path',
        'dihantar_pemakluman_pada',
    ];

    protected $casts = [
        'tarikh_mesyuarat' => 'date',
        'dihantar_pemakluman_pada' => 'datetime',
    ];

    public function tender()
    {
        return $this->belongsTo(Tender::class, 'tender_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
