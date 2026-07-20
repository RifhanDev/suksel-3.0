<?php

namespace App\Models;

use App\Tender;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JawatankuasaPerolehanPemilihanItem extends Model
{
    protected $table = 'jawatankuasa_perolehan_pemilihan_items';

    protected $fillable = [
        'tender_id',
        'sort_order',
        'perihal_item',
        'jenis_item',
        'unit_ukuran',
        'jenis_harga',
        'dibatalkan',
        'pembekal_dipilih',
        'kuantiti',
    ];

    protected $casts = [
        'kuantiti' => 'decimal:4',
    ];

    public function tender(): BelongsTo
    {
        return $this->belongsTo(Tender::class, 'tender_id');
    }

    public function petenders(): HasMany
    {
        return $this->hasMany(JawatankuasaPerolehanPemilihanPetender::class, 'pemilihan_item_id')->orderBy('sort_order');
    }
}
