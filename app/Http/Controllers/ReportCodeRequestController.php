<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportCodeRequestController extends Controller
{
    public function index()
    {
        if (!auth()->check() || !auth()->user()->ability(['Admin'], ['Report:view:code_request'])) {
            return $this->_access_denied();
        }
        return view('reports.vendor.code-request.index');
    }

    public function view(Request $request)
    {
        if (!auth()->check() || !auth()->user()->ability(['Admin'], ['Report:view:code_request'])) {
            return $this->_access_denied();
        }
        // dd($request->all());
        $type = $request->type;
        $year = $request->year;
        $week = $request->week;
        $month = date('F Y', strtotime($request->month));

        if ($type == 'year') {
            $responses = DB::table('view_code_request_report')->where('year', $year)->orderBy('type')->orderBy('created_at')->get();
        } else if ($type == 'month') {
            $newYear = date('Y', strtotime($month));
            $newMonth = date('m', strtotime($month));
            $responses = DB::table('view_code_request_report')->where('year', $newYear)->where('month', $newMonth)->orderBy('type')->orderBy('created_at')->get();
        } else if ($type == 'week') {
            $newWeek = date('YW', strtotime($week));
            $week = date('W, Y', strtotime($week));
            // dd($week);
            $responses = DB::table('view_code_request_report')->where('week', $newWeek)->orderBy('type')->orderBy('created_at')->get();
        }

        return view('reports.vendor.code-request.view', compact('responses', 'type', 'year', 'month', 'week'));
    }
}
