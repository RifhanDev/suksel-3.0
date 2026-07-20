<?php

namespace App\Models;

use App\Tender;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JawatankuasaPerolehanPemilihanHeader extends Model
{
    protected $table = 'jawatankuasa_perolehan_pemilihan_headers';

    protected $fillable = [
        'tender_id',
        'keputusan_mesyuarat',
        'kaedah_memuktamadkan_pembekal',
        'pemilihan_berdasarkan',
        'loi_loa_disediakan_oleh',
        'bil_mesyuarat',
        'no_kod',
        'sahkan_layak_bidaan',
        'submitted_at',
    ];

    protected $casts = [
        'sahkan_layak_bidaan' => 'boolean',
        'submitted_at' => 'datetime',
    ];

    public function tender(): BelongsTo
    {
        return $this->belongsTo(Tender::class, 'tender_id');
    }
}
