<?php

namespace App\Models;

use App\Tender;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EbiddingJadualBidaan extends Model
{
    protected $table = 'ebidding_jadual_bidaans';

    protected $fillable = [
        'tender_id',
        'tarikh_bidaan_mula',
        'masa_bidaan_mula',
        'tarikh_bidaan_tamat',
        'masa_bidaan_tamat',
        'started_at',
        'submitted_at',
    ];

    protected $casts = [
        'tarikh_bidaan_mula' => 'date',
        'tarikh_bidaan_tamat' => 'date',
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    public function tender(): BelongsTo
    {
        return $this->belongsTo(Tender::class, 'tender_id');
    }

    /**
     * H:i for time inputs. Stored TIME values come back as H:i:s, which browsers
     * then resubmit and fail a strict H:i check.
     */
    public function timeForInput(string $field): string
    {
        return self::normalizeTime($this->schedulePart($field), false);
    }

    /**
     * @return array{has_schedule: bool, is_open: bool, has_started: bool, has_ended: bool, starts_at: ?string, ends_at: ?string, ends_at_ms: ?int}
     */
    public function windowState(): array
    {
        $startAt = self::combine(
            $this->schedulePart('tarikh_bidaan_mula'),
            $this->schedulePart('masa_bidaan_mula')
        );
        $endAt = self::combine(
            $this->schedulePart('tarikh_bidaan_tamat'),
            $this->schedulePart('masa_bidaan_tamat')
        );

        return self::describeWindow($startAt, $endAt);
    }

    /**
     * @return array{has_schedule: bool, is_open: bool, has_started: bool, has_ended: bool, starts_at: ?string, ends_at: ?string, ends_at_ms: ?int}
     */
    public static function emptyWindow(): array
    {
        return self::describeWindow(null, null);
    }

    public static function combine(mixed $date, mixed $time): ?Carbon
    {
        if ($date instanceof \DateTimeInterface) {
            $date = $date->format('Y-m-d');
        }

        $date = substr(trim((string) $date), 0, 10);
        $time = self::normalizeTime($time, true);
        if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) || $time === '') {
            return null;
        }

        try {
            return Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $time, self::timezone());
        } catch (\Throwable) {
            return null;
        }
    }

    public static function normalizeTime(mixed $time, bool $withSeconds = false): string
    {
        $time = trim((string) $time);
        if (! preg_match('/^(\d{1,2}):(\d{2})(?::(\d{2}))?/', $time, $matches)) {
            return '';
        }

        $formatted = sprintf('%02d:%02d:%02d', (int) $matches[1], (int) $matches[2], (int) ($matches[3] ?? 0));

        return $withSeconds ? $formatted : substr($formatted, 0, 5);
    }

    /**
     * @return array{has_schedule: bool, is_open: bool, has_started: bool, has_ended: bool, starts_at: ?string, ends_at: ?string, ends_at_ms: ?int}
     */
    private static function describeWindow(?Carbon $startAt, ?Carbon $endAt): array
    {
        if (! $startAt || ! $endAt) {
            return [
                'has_schedule' => false,
                'is_open' => false,
                'has_started' => false,
                'has_ended' => false,
                'starts_at' => null,
                'ends_at' => null,
                'ends_at_ms' => null,
            ];
        }

        $now = Carbon::now(self::timezone());

        return [
            'has_schedule' => true,
            'is_open' => $now->betweenIncluded($startAt, $endAt),
            'has_started' => $now->greaterThanOrEqualTo($startAt),
            'has_ended' => $now->greaterThan($endAt),
            'starts_at' => $startAt->toIso8601String(),
            'ends_at' => $endAt->toIso8601String(),
            'ends_at_ms' => (int) $endAt->valueOf(),
        ];
    }

    private function schedulePart(string $field): mixed
    {
        $raw = $this->getRawOriginal($field);
        if ($raw !== null && $raw !== '') {
            return $raw;
        }

        return $this->getAttribute($field);
    }

    private static function timezone(): string
    {
        return (string) (config('app.timezone') ?: 'Asia/Kuala_Lumpur');
    }
}
