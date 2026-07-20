<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VersionHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'version',
        'released_at',
        'notes',
    ];

    protected $casts = [
        'released_at' => 'date',
    ];

    /**
     * Get notes as array of lines (for list display).
     */
    public function getNotesLinesAttribute(): array
    {
        if (empty($this->notes)) {
            return [];
        }
        return array_filter(array_map('trim', explode("\n", $this->notes)));
    }

    /**
     * Format released_at for display (e.g. "8 Jun 2015").
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->released_at ? $this->released_at->format('j F Y') : '';
    }

    public static function canList(): bool
    {
        return auth()->check() && auth()->user()->ability(['Admin'], []);
    }

    public static function canCreate(): bool
    {
        return auth()->check() && auth()->user()->ability(['Admin'], []);
    }

    public function canUpdate(): bool
    {
        return auth()->check() && auth()->user()->ability(['Admin'], []);
    }

    public function canDelete(): bool
    {
        return auth()->check() && auth()->user()->ability(['Admin'], []);
    }
}
