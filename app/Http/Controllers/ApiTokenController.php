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
                    return $apitoken->status == 1 ? 'AKTIF' : 'TIDAK AKTIF';
                })
                ->removeColumn('id')
                ->rawColumns(['organization_unit_id', 'created_at', 'created_at'])
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
