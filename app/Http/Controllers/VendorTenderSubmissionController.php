<?php

namespace App\Http\Controllers;

use App\Services\VendorTenderSubmissionService;
use App\Tender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorTenderSubmissionController extends Controller
{
    public function __construct(protected VendorTenderSubmissionService $submissions) {}

    public function readiness(Tender $tender)
    {
        $this->assertOnlineSubmissionAllowed($tender);

        $vendorId = $this->vendorId();

        $result = $this->submissions->readiness($tender, $vendorId);
        $purchase = $result['purchase'];

        return response()->json([
            'success' => true,
            'ready' => $result['ready'],
            'errors' => $result['errors'],
            'submitted' => $purchase ? (bool) $purchase->submitted : false,
            'kod_pembekal' => $purchase?->kod_pembekal,
        ]);
    }

    public function show(Tender $tender)
    {
        return redirect()->route('tenders.show', $tender->id);
    }

    public function submit(Request $request, Tender $tender)
    {
        $this->assertOnlineSubmissionAllowed($tender);

        $vendorId = $this->vendorId();

        $purchase = $this->submissions->submit($tender, $vendorId);

        $payload = [
            'success' => true,
            'message' => 'Tawaran berjaya dihantar. Maklumat tidak boleh dikemaskini selepas ini.',
            'data' => [
                'submitted' => true,
                'kod_pembekal' => $purchase->kod_pembekal,
                'status_process_id' => (int) $tender->fresh()->status_process_id,
            ],
        ];

        if (! $request->expectsJson()) {
            return redirect()
                ->route('tenders.show', $tender->id)
                ->with('success', $payload['message']);
        }

        return response()->json($payload);
    }

    protected function vendorId(): int
    {
        $user = Auth::user();

        if (! $user || ! $user->vendor_id) {
            abort(403, 'Akses vendor diperlukan.');
        }

        return (int) $user->vendor_id;
    }

    /**
     * Bayaran Dokumen Secara Online / Pembelian Manual (Iklan Sahaja):
     * tawaran dihantar secara manual ke kaunter, bukan melalui sistem.
     */
    protected function assertOnlineSubmissionAllowed(Tender $tender): void
    {
        $tender->loadMissing('kaedahDokumen');

        if ($tender->usesIklanDokumen()) {
            abort(403, 'Kaedah dokumen ini tidak memerlukan penghantaran tawaran melalui sistem.');
        }
    }
}
