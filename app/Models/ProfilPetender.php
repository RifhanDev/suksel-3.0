<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilPetender extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'tender_id',
        'nama_syarikat',
        'jenis_syarikat',
        'taraf_petender',
        'alamat',
        'pegawai_nama',
        'pegawai_telefon',
        'pegawai_emel',
        'no_ssm',
        'no_mof',
        'tempoh_sah_mof',
        'bil_pekerja',
        'bil_pekerja_teknikal',
        'modal_berbayar',
        'modal_dibenarkan',
        'aset',
        'peralatan',
        'tanggungan',
        'baki_wang_bank',
        'jenis_skor_modal_berbayar',
        'jenis_skor_modal_dibenarkan',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'aset'             => 'array',
        'peralatan'        => 'array',
        'modal_berbayar'   => 'decimal:2',
        'modal_dibenarkan' => 'decimal:2',
        'tanggungan'       => 'decimal:2',
        'baki_wang_bank'   => 'decimal:2',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }

    public function projects()
    {
        return $this->hasMany(ProfilPetenderProject::class)->orderBy('sort_order');
    }

    public function scoringItems()
    {
        return $this->hasMany(ProfilPetenderScoringItem::class)->orderBy('sort_order');
    }
}
