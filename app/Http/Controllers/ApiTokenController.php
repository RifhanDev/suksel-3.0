<?php

namespace App\Http\Controllers;

use Datatables;
use App\Models\ApiToken;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\OrganizationUnit;

class ApiTokenController extends Controller
{
    public function index(Request $request)
    {
        if (!ApiToken::canList())
            return $this->_access_denied();

        if ($request->ajax()) {
            $apitokens = ApiToken::select('*');
            $apitokens = $apitokens->with('agency');

            return Datatables::of($apitokens)
                ->editColumn('organization_unit_id', function ($apitoken) {
                    return $apitoken->agency->name;
                })
                ->editColumn('created_at', function ($apitoken) {
                    return Carbon::parse($apitoken->created_at)->format('j M Y');
                })
                ->editColumn('status', function ($apitoken) {
                    return $apitoken->status == 1
                        ? '<span class="badge-bool-yes d-inline-flex align-items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"><path fill="currentColor" d="M17 3.34a10 10 0 1 1-14.995 8.984L2 12l.005-.324A10 10 0 0 1 17 3.34m-1.293 5.953a1 1 0 0 0-1.32-.083l-.094.083L11 12.585l-1.293-1.292l-.094-.083a1 1 0 0 0-1.403 1.403l.083.094l2 2l.094.083a1 1 0 0 0 1.226 0l.094-.083l4-4l.083-.094a1 1 0 0 0-.083-1.32"/></svg> Aktif</span>'
                        : '<span class="badge-bool-no d-inline-flex align-items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg> Tidak Aktif</span>';
                })
                ->removeColumn('id')
                ->rawColumns(['organization_unit_id', 'created_at', 'status'])
                ->make();
        }

        return view('api.index');
    }

    public function create()
    {
        if (!ApiToken::canCreate())
            return $this->_access_denied();

        $exist_agency_id = ApiToken::pluck('organization_unit_id')->all();

        $agencies = OrganizationUnit::select('id', 'name')->whereNotIn('id', $exist_agency_id)->get();

        return view('api.create', compact('agencies'));
    }

    public function store(Request $request)
    {

        if (!ApiToken::canCreate())
            return $this->_access_denied();

        $data = $request->all();
        // dd($data);
        $token = new ApiToken;
        $token->fill($data);

        if (!$token->save())
            return $this->_validation_error($token);

        return redirect('apitoken')->with('success', $this->created_message);
    }

    public function generateToken(Request $request)
    {
        $agency_id = $request->id;

        $token = Str::uuid($agency_id);

        return response()->json($token);
    }
}
