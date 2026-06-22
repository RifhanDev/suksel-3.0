<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LembaranImbangan extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'tender_id',
        'aset_tetap',
        'aset_semasa',
        'liabiliti_semasa',
        'liabiliti_tetap',
        'wang_tunai',
        'baki_kemudahan_kredit',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'aset_tetap'             => 'decimal:2',
        'aset_semasa'            => 'decimal:2',
        'liabiliti_semasa'       => 'decimal:2',
        'liabiliti_tetap'        => 'decimal:2',
        'wang_tunai'             => 'decimal:2',
        'baki_kemudahan_kredit'  => 'decimal:2',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }
}
