<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PerakuanJabatanKertasTaklimatItem extends Model
{
    protected $table = 'perakuan_jabatan_kertas_taklimat_items';

    protected $fillable = [
        'kertas_taklimat_id',
        'slot_key',
        'kandungan',
        'sort_order',
    ];

    /** @return 'download_only'|'upload_only'|'upload_download' */
    public static function actionUi(?string $slotKey): string
    {
        return match ($slotKey) {
            'teknikal' => 'download_only',
            'kewangan' => 'upload_download',
            'ringkasan' => 'upload_only',
            'kertas_perakuan', null => 'upload_download',
            default => 'upload_download',
        };
    }

    public function header(): BelongsTo
    {
        return $this->belongsTo(PerakuanJabatanKertasTaklimat::class, 'kertas_taklimat_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(PerakuanJabatanKertasTaklimatItemFile::class, 'item_id');
    }
}
