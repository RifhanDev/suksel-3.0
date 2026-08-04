<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenderTeknikalLaporan extends Model
{
    protected $table = 'tender_teknikal_laporans';

    protected $fillable = [
        'tender_id',
        'pematuhan_confirmed_at',
        'spesifikasi_confirmed_at',
        'catatan_pematuhan',
        'catatan_spesifikasi',
        'pengesyoran_intro',
        'pengesyoran_justifikasi',
        'winning_vendor_id',
        'status',
        'submitted_at',
        'submitted_by',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'pengesyoran_justifikasi' => 'array',
        'submitted_at' => 'datetime',
        'pematuhan_confirmed_at' => 'datetime',
        'spesifikasi_confirmed_at' => 'datetime',
    ];

    public function tender()
    {
        return $this->belongsTo(\App\Tender::class);
    }

    public function winningVendor()
    {
        return $this->belongsTo(\App\Vendor::class, 'winning_vendor_id');
    }
}
