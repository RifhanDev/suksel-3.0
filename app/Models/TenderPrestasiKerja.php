<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenderPrestasiKerja extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'tender_id',
        'vendor_id',
        'status',
        'created_by',
        'updated_by',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }

    public function items()
    {
        return $this->hasMany(TenderPrestasiKerjaItem::class, 'tender_prestasi_kerja_id')->orderBy('sort_order');
    }

    public function dokumens()
    {
        return $this->hasMany(TenderPrestasiKerjaDokumen::class, 'tender_prestasi_kerja_id');
    }
}
