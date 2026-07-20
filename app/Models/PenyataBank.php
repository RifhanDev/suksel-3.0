<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenyataBank extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'tender_id',
        'dari_bulan',
        'dari_tahun',
        'hingga_bulan',
        'hingga_tahun',
        'jumlah_keseluruhan',
        'purata',
        'jenis_skor_purata',
        'accounts',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'jumlah_keseluruhan' => 'decimal:2',
        'purata'             => 'decimal:2',
        'accounts'           => 'array',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }

    public function bulans()
    {
        return $this->hasMany(PenyataBankBulan::class)->orderBy('tahun')->orderBy('bulan');
    }

    public function scoringItems()
    {
        return $this->hasMany(PenyataBankScoringItem::class)->orderBy('sort_order');
    }

    public function files()
    {
        return $this->hasMany(PenyataBankFile::class);
    }
}
