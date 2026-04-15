<?php

namespace App\Http\Controllers;

use App\Tender;
use Carbon\Carbon;

class EbiddingController extends Controller
{
    /**
     * Senarai tender yang telah melengkapkan Jawatankuasa Perolehan (status 4)
     dan ditandakan untuk proses e-bidding.
     */
    public function index()
    {
        $rows = Tender::query()
            ->where('status_process_id', 4)
            ->where('is_ebidding', true)
            ->orderByDesc('id')
            ->get([
                'id',
                'uuid',
                'no_tender',
                'ref_number',
                'name',
                'submission_datetime',
            ])
            ->map(function (Tender $tender) {
                $submissionDate = null;
                if (!empty($tender->submission_datetime)) {
                    $submissionDate = Carbon::parse($tender->submission_datetime);
                }

                $noTender = $tender->no_tender ?: $tender->ref_number ?: '-';
                $tajukPlain = $tender->name ?: '-';
                $link = route('keputusanMesyuarat', ['tender' => $tender->uuid]);

                return [
                    'no_tender' => $noTender,
                    'tajuk_plain' => $tajukPlain,
                    'tajuk_html' => '<a href="' . e($link) . '" class="fw-semibold text-primary text-decoration-none">' . e($tajukPlain) . '</a>',
                    'tarikh' => $submissionDate ? $submissionDate->format('d/m/Y') : '-',
                    'status_key' => 'Dalam Proses',
                    'status_html' => '<span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill small fw-semibold" style="background:#fef3c7;color:#b45309;">'
                        . '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>'
                        . ' Dalam Proses</span>',
                ];
            })
            ->values();

        return view('newModule.eBidding.index', compact('rows'));
    }
}
