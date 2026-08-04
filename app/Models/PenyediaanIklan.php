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
                'catatan' => null,
                'dokumen' => null,
            ],
        ];
    }

    /**
     * Drop removed fixed rows (e.g. Kelulusan Projek ICT) from stored meta.
     *
     * @param  array<int, array<string, mixed>>  $rows
     * @return array<int, array<string, mixed>>
     */
    public static function normalizeKelulusanRows(array $rows): array
    {
        $normalized = [];
        $hasBerbelanja = false;

        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }

            $jenis = trim((string) ($row['jenis'] ?? ''));
            if ($jenis === 'Kelulusan Projek ICT' && ! empty($row['is_fixed'])) {
                continue;
            }

            unset($row['status']);

            if ($jenis === 'Kelulusan Berbelanja' && ! empty($row['is_fixed'])) {
                $hasBerbelanja = true;
                $row['is_fixed'] = true;
            }

            $normalized[] = $row;
        }

        if (! $hasBerbelanja) {
            array_unshift($normalized, self::defaultKelulusan()[0]);
        }

        return array_values($normalized);
    }
}
