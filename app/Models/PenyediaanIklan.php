<?php

namespace App\Models;

use App\Tender;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenyediaanIklan extends Model
{
    protected $fillable = [
        'tender_id',
        'meta',
        'submitted_at',
    ];

    protected $casts = [
        'meta' => 'array',
        'submitted_at' => 'datetime',
    ];

    public function tender(): BelongsTo
    {
        return $this->belongsTo(Tender::class);
    }

    public static function defaultKelulusan(): array
    {
        return [
            [
                'jenis' => 'Kelulusan Berbelanja',
                'is_fixed' => true,
                'status' => null,
                'catatan' => null,
                'dokumen' => null,
            ],
            [
                'jenis' => 'Kelulusan Projek ICT',
                'is_fixed' => true,
                'status' => null,
                'catatan' => null,
                'dokumen' => null,
            ],
        ];
    }
}
