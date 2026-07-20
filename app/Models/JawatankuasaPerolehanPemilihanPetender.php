<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JawatankuasaPerolehanPemilihanPetender extends Model
{
    protected $table = 'jawatankuasa_perolehan_pemilihan_petenders';

    protected $fillable = [
        'pemilihan_item_id',
        'vendor_id',
        'sort_order',
        'bil_label',
        'status_bumiputra',
        'harga_tawaran',
        'jumlah_skor',
        'kedudukan_penilaian',
        'status_mof',
        'tindakan_disiplin',
        'lembaga_pengarah_file_path',
        'keputusan_urusetia',
        'catatan_urusetia',
    ];

    protected $casts = [
        'harga_tawaran' => 'decimal:2',
        'jumlah_skor' => 'decimal:2',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(JawatankuasaPerolehanPemilihanItem::class, 'pemilihan_item_id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(\App\Vendor::class, 'vendor_id');
    }
}
