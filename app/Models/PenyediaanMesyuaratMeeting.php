<?php

namespace App\Models;

use App\Tender;
use Illuminate\Database\Eloquent\Model;

class PenyediaanMesyuaratMeeting extends Model
{
    protected $table = 'penyediaan_mesyuarat';

    protected $fillable = [
        'tender_id',
        'jenis_jawatankuasa',
        'tarikh_mesyuarat',
        'masa',
        'tempat',
        'status',
        'submitted_at',
    ];

    protected $casts = [
        'tarikh_mesyuarat' => 'date',
        'submitted_at' => 'datetime',
    ];

    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }
}
