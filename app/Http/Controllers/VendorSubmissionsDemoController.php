<?php

namespace App\Http\Controllers;

use App\Support\TenderDokumenPresenter;
use App\Tender;
use Illuminate\Http\Request;

/**
 * Dummy reference page — shows how to load all vendor checklist submissions
 * for a tender after vendors have purchased the document.
 */
class VendorSubmissionsDemoController extends Controller
{
    public function index()
    {
        $tenders = Tender::query()
            ->withCount(['participants as buyer_count' => fn ($q) => $q->where('participate', 1)])
            ->orderByDesc('id')
            ->limit(30)
            ->get(['id', 'uuid', 'name', 'no_tender', 'ref_number']);

        return view('demo.vendor_submissions.index', compact('tenders'));
    }

    public function show(Request $request, Tender $tender)
    {
        $tender->loadMissing('tenderer');

        $tenderDokumen = TenderDokumenPresenter::for($tender);
        $checklistItems = $tenderDokumen->items('admin');

        $participants = $tender->participants()
            ->where('participate', 1)
            ->with('vendor')
            ->orderBy('id')
            ->get();

        $submissions = [];
        foreach ($participants as $participant) {
            $vendorId = (int) $participant->vendor_id;

            $submissions[$vendorId] = [
                'vendor_id' => $vendorId,
                'vendor_name' => $participant->vendor?->name ?: ('Vendor #' . $vendorId),
                'kod_pembekal' => $participant->kod_pembekal,
                'items' => $tenderDokumen->items('vendor', $vendorId),
            ];
        }

        $allUploads = $this->flattenVendorUploads($submissions);

        return view('demo.vendor_submissions.show', [
            'tender' => $tender,
            'tenderDokumen' => $tenderDokumen,
            'checklistItems' => $checklistItems,
            'submissions' => $submissions,
            'allUploads' => $allUploads,
            'buyerCount' => count($submissions),
        ]);
    }

    /**
     * @param  array<int, array{vendor_id: int, vendor_name: string, items: array<int, array<string, mixed>>}>  $submissions
     * @return array<int, array<string, mixed>>
     */
    protected function flattenVendorUploads(array $submissions): array
    {
        return collect($submissions)->flatMap(function (array $row) {
            $vendorId = $row['vendor_id'];
            $vendorName = $row['vendor_name'];

            return collect($row['items'])->flatMap(function (array $item) use ($vendorId, $vendorName) {
                return collect($item['vendor_content']['files'] ?? [])->map(fn (array $file) => [
                    'vendor_id' => $vendorId,
                    'vendor_name' => $vendorName,
                    'item_uuid' => $item['uuid'] ?? null,
                    'item_title' => $item['title'] ?? $item['nama'] ?? '-',
                    'action' => $item['action'] ?? '-',
                    'file_uuid' => $file['uuid'] ?? null,
                    'file_name' => $file['name'] ?? 'Dokumen',
                    'download_url' => $file['url'] ?? '#',
                    'file_size' => $file['size'] ?? null,
                ]);
            });
        })->values()->all();
    }
}
