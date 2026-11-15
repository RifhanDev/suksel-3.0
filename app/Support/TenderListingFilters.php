<?php

namespace App\Support;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

final class TenderListingFilters
{
    /**
     * Apply the common tender number, title, and date filters to an already-scoped query.
     * The date filter matches any of the sale, closing, or document dates used in tender lists.
     */
    public static function apply(Builder $query, Request $request): Builder
    {
        if ($request->filled('no_tender')) {
            $number = trim((string) $request->input('no_tender'));
            $query->where(function (Builder $nested) use ($number) {
                $nested->where('tenders.no_tender', 'like', '%' . $number . '%')
                    ->orWhere('tenders.ref_number', 'like', '%' . $number . '%');
            });
        }

        if ($request->filled('tajuk')) {
            $title = trim((string) $request->input('tajuk'));
            $query->where('tenders.name', 'like', '%' . $title . '%');
        }

        if ($request->filled('tarikh')) {
            $inputDate = trim((string) $request->input('tarikh'));
            $date = null;

            foreach (['d/m/Y', 'Y-m-d'] as $format) {
                try {
                    $parsed = Carbon::createFromFormat('!' . $format, $inputDate);
                    if ($parsed && $parsed->format($format) === $inputDate) {
                        $date = $parsed->format('Y-m-d');
                        break;
                    }
                } catch (\Throwable $exception) {
                    // Try the next supported date format.
                }
            }

            if ($date === null) {
                $query->whereRaw('1 = 0');
            } else {
                $query->where(function (Builder $nested) use ($date) {
                    $nested->whereDate('tenders.document_start_date', $date)
                        ->orWhereDate('tenders.submission_datetime', $date)
                        ->orWhereDate('tenders.advertise_start_date', $date)
                        ->orWhereDate('tenders.advertise_stop_date', $date);
                });
            }
        }

        return $query;
    }
}
