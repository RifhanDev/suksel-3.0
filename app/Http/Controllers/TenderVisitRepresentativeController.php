<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TenderVisitRepresentative;
use App\TenderVisit;
use App\TenderVisitor;
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

        $visit = TenderVisit::findOrFail($visitId);

        $data = $request->validate([
            'reps'                 => 'array',
            'reps.*.ic_no'         => 'nullable|string|max:32',
            'reps.*.name'          => 'nullable|string|max:255',
            'reps.*.attended'      => 'nullable|boolean',
        ]);

        // Clear existing reps for this vendor + visit
        TenderVisitRepresentative::where('visit_id', $visit->id)
            ->where('vendor_id', $user->vendor_id)
            ->delete();

        $hasAttended = false;

        if (!empty($data['reps'])) {
            foreach ($data['reps'] as $rep) {
                if (empty($rep['ic_no']) && empty($rep['name'])) {
                    continue;
                }

                $attended = !empty($rep['attended']);
                if ($attended) {
                    $hasAttended = true;
                }

                TenderVisitRepresentative::create([
                    'visit_id' => $visit->id,
                    'vendor_id' => $user->vendor_id,
                    'ic_no' => $rep['ic_no'] ?? null,
                    'name' => $rep['name'] ?? null,
                    'attended' => $attended,
                ]);
            }
        }

        if ($hasAttended && !TenderVisitor::hasVisit($visit->id, $user->vendor_id)) {
            TenderVisitor::create([
                'visit_id' => $visit->id,
                'vendor_id' => $user->vendor_id,
            ]);
        }

        if (!$hasAttended && TenderVisitor::hasVisit($visit->id, $user->vendor_id)) {
            TenderVisitor::where('visit_id', $visit->id)
                ->where('vendor_id', $user->vendor_id)
                ->delete();
        }

        return response()->json(['message' => 'Berjaya disimpan.', 'attended' => $hasAttended]);
    }
}

