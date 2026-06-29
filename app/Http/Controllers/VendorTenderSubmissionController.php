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

    public function submit(Request $request, Tender $tender)
    {
        $vendorId = $this->vendorId();

        $purchase = $this->submissions->submit($tender, $vendorId);

        return response()->json([
            'success' => true,
            'message' => 'Tawaran berjaya dihantar. Maklumat tidak boleh dikemaskini selepas ini.',
            'data' => [
                'submitted' => true,
                'kod_pembekal' => $purchase->kod_pembekal,
                'status_process_id' => (int) $tender->fresh()->status_process_id,
            ],
        ]);
    }

    protected function vendorId(): int
    {
        $user = Auth::user();

        if (! $user || ! $user->vendor_id) {
            abort(403, 'Akses vendor diperlukan.');
        }

        return (int) $user->vendor_id;
    }
}
