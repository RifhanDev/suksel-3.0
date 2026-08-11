<?php

namespace App\Models;

use App\Tender;
use App\User;
use Illuminate\Database\Eloquent\Model;

class TenderKewanganLaporan extends Model
{
    protected $table = 'tender_kewangan_laporans';

    protected $fillable = [
        'tender_id',
        'catatan_peringkat1',
        'catatan_peringkat2',
        'catatan_peringkat3',
        'pengesyoran_justifikasi',
        'status',
        'submitted_at',
        'submitted_by',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'pengesyoran_justifikasi' => 'array',
        'submitted_at'            => 'datetime',
    ];

    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }

    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }
}
