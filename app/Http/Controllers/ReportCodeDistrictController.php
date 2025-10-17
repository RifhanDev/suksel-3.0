<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportCodeDistrictController extends Controller
{
    public function index()
    {
        if (!auth()->check() || !auth()->user()->ability(['Admin'], ['Report:view:code_district'])) {
            return $this->_access_denied();
        }
        return view('reports.code.district.index');
    }

    public function view(Request $request)
    {
        if (!auth()->check() || !auth()->user()->ability(['Admin'], ['Report:view:code_district'])) {
            return $this->_access_denied();
        }
        $type = $request->type;
        $responses = null;

        if ($type == 'active') {
            $responses = DB::table('active_vendor_kod_bidang_count')->get();
        } else if ($type == 'register') {
            $responses = DB::table('pending_approval_vendor_kod_bidang_count')->get();
        } else if ($type == 'update') {
            $responses = DB::table('pending_update_vendor_kod_bidang_count')->get();
        }

        return view('reports.code.district.view', compact('responses','type'));
    }
}
