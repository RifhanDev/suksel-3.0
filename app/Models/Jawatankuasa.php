<?php

namespace App\Models;

use App\Tender;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Jawatankuasa extends Model
{
    protected $table = 'jawatankuasas';

    protected $fillable = [
        'tender_id',
        'jenis_jawatankuasa',
        'p_p',
        'peranan',
        'user_id',
        'catatan',
        'dokumen_sokongan_nama',
        'dokumen_sokongan_path',
        'dihantar_pemakluman_pada',
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
