<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerakuanJabatanPengesyoranPembekalItem extends Model
{
    protected $table = 'perakuan_jabatan_pengesyoran_pembekal_items';

    public const SYOR_DISYORKAN = 'Disyorkan';
    public const SYOR_DITOLAK = 'Ditolak';
    public const SYOR_DIPERTIMBANG = 'Dipertimbang';

    public const SYOR_OPTIONS = [
        self::SYOR_DISYORKAN,
        self::SYOR_DITOLAK,
        self::SYOR_DIPERTIMBANG,
    ];

    protected $fillable = [
        'pengesyoran_pembekal_id',
        'vendor_id',
        'syor_urusetia',
        'catatan_urusetia',
    ];

    public function pengesyoran(): BelongsTo
    {
        return $this->belongsTo(PerakuanJabatanPengesyoranPembekal::class, 'pengesyoran_pembekal_id');
    }
}
