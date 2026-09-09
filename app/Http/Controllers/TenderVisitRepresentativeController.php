<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TenderVisitRepresentative;
use App\TenderVisit;
use Illuminate\Http\Request;

class TenderVisitRepresentativeController extends Controller
{
    public function index($visitId)
    {
        $user = auth()->user();

        if (!$user || !$user->vendor_id) {
            return response()->json([], 200);
        }

        $reps = TenderVisitRepresentative::where('visit_id', $visitId)
            ->where('vendor_id', $user->vendor_id)
            ->orderBy('id')
            ->get(['id', 'ic_no', 'name', 'attended']);

        return response()->json($reps);
    }

    public function store(Request $request, $visitId)
    {
        $user = auth()->user();

        if (!$user || !$user->vendor_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $visit = TenderVisit::with('tender')->findOrFail($visitId);

        if (!$visit->tender) {
            return response()->json(['message' => 'Tender tidak dijumpai.'], 404);
        }

        if (!$visit->canSubmitRepresentatives()) {
            return response()->json([
                'message' => 'Pendaftaran wakil hanya dibenarkan sehingga tarikh lawatan tapak.',
            ], 403);
        }

        app(\App\Services\VendorTenderSubmissionService::class)
            ->assertEditable($visit->tender, (int) $user->vendor_id);

        $repsInput = $request->input('reps', []);
        if (is_array($repsInput)) {
            foreach ($repsInput as $i => $rep) {
                if (!is_array($rep)) {
                    continue;
                }
                if (array_key_exists('ic_no', $rep)) {
                    $digits = preg_replace('/\D+/', '', (string) $rep['ic_no']);
                    $repsInput[$i]['ic_no'] = $digits === '' ? null : substr($digits, 0, 12);
                }
            }
            $request->merge(['reps' => $repsInput]);
        }

        $data = $request->validate([
            'reps'                 => 'array',
            'reps.*.ic_no'         => 'nullable|digits:12',
            'reps.*.name'          => 'nullable|string|max:255',
        ]);

        TenderVisitRepresentative::where('visit_id', $visit->id)
            ->where('vendor_id', $user->vendor_id)
            ->delete();

        if (!empty($data['reps'])) {
            foreach ($data['reps'] as $rep) {
                if (empty($rep['ic_no']) && empty($rep['name'])) {
                    continue;
                }

                TenderVisitRepresentative::create([
                    'visit_id' => $visit->id,
                    'vendor_id' => $user->vendor_id,
                    'ic_no' => $rep['ic_no'] ?? null,
                    'name' => $rep['name'] ?? null,
                    'attended' => false,
                ]);
            }
        }

        return response()->json(['message' => 'Berjaya disimpan.']);
    }
}
