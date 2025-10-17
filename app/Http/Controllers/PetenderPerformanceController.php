<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Tender;
use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Models\TenderHistory;
use App\Models\RejectTemplate;
use App\Models\PetenderPerformance;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PetenderPerformanceRequest;

class PetenderPerformanceController extends Controller
{
    /**
     * Store PetenderPerformance model
     */
    public function store(PetenderPerformanceRequest $request, Tender $tender, Vendor $vendor)
    {
        $validated = $request -> validated();

        if(!$tender -> canShowTabs())
        {
            return $this -> _access_denied();
        }

        if (isset($validated['type1']))
        {
            $type = $validated['type1'];
        }
        else
        {
            $type = $validated['type2'];
        }

        // Store
        $petenderPerformance = PetenderPerformance::create([
            'type'             => $type,
            'quantity'         => $validated['quantity'],
            'cost'             => $validated['cost'],
            'acquisition_date' => $validated['acquisition_date'],
            'opinion'          => $validated['opinion'],
            'overall_review'   => $validated['review'],
            'total_score'      => $validated['total_score'],
            'vendor_id'        => $vendor -> id,
            'tender_id'        => $tender -> id,
            'appraiser_id'     => Auth::user() -> id
        ]);

        $petenderPerformance -> performanceCriteria() -> create([
            'scale_1'  => $validated['scale_1'],
            'scale_2'  => $validated['scale_2'],
            'scale_3'  => $validated['scale_3'],
            'scale_4'  => $validated['scale_4'],
            'scale_5'  => $validated['scale_5'],
            'scale_6'  => $validated['scale_6'],
            'review_1' => $validated['review_1'],
            'review_2' => $validated['review_2'],
            'review_3' => $validated['review_3'],
            'review_4' => $validated['review_4'],
            'review_5' => $validated['review_5'],
            'review_6' => $validated['review_6'],
        ]);

        // Log
        TenderHistory::log($tender -> id, 'rate-vendor-tender');

        view() -> share('global_ou', $tender -> tenderer);

        // Return
        return back()
        -> with('success', 'Penilaian Syarikat ' . $vendor -> name . ' telah dibuat bagi tender ' . $tender -> ref_number);
    }

    /**
     * Index - Show vendor's petender performance list
     */
    public function vendorPetender(Request $request, Tender $tender)
    {
		if (!auth() -> check())
			return $this -> _access_denied();

		$user_id = auth() -> user() -> vendor_id;

		$tender = Tender::with('codes')
			-> with('siteVisits','creator','officer')
			-> findOrFail($tender -> id);

		$organizationunit = $tender -> tenderer;
		$invites          = $tender -> invites() -> has('vendor') -> get();
		$histories        = $tender -> histories() -> orderBy('created_at', 'desc') -> get();
		$tender_winner    = null;
		$tender_vendors   = $tender -> participants;

		foreach($tender_vendors as $tender_vendor)
		{
			if($tender_vendor -> winner == 1)
			{
				$tender_winner = $tender_vendor;
			}
		}
		
		// Temporary fix since this code prevent me from accessing my module field
		$exception = null;
		$templates = null;

		if(!$tender -> canShow()) {
			$exception          = $tender->exceptions()->with('files')->where('vendor_id', $user_id)->orderBy('created_at', 'desc')->first();
			$templates 			= RejectTemplate::where('applicable', 3)->get(['id', 'title', 'content']);
		}

		if (!$tender -> canShow()) {
			return $this -> _access_denied();
		}
		if ($request -> ajax()) {
			return response() -> json($tender);
		}

        // Activate content tab for Rekod Penilaian Prestasi Syarikat
        $active_prestasi_tab = 'active';
        $active_sebut_harga_tab = 'active';

		view() -> share('global_ou', $tender -> tenderer);
		return view('tenders.show', compact('tender', 'organizationunit', 'invites', 'histories', 'exception', 'templates', 'tender_winner', 'active_prestasi_tab', 'active_sebut_harga_tab'));
    }
}
