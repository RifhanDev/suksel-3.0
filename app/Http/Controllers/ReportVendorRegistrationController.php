<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportVendorRegistrationController extends Controller
{
    public function index()
    {
        if (!auth()->check() || !auth()->user()->ability(['Admin'], ['Report:view:vendor_registration'])) {
            return $this->_access_denied();
        }
        return view('reports.vendor.registration.index');
    }

    public function view(Request $request)
    {
        if (!auth()->check() || !auth()->user()->ability(['Admin'], ['Report:view:vendor_registration'])) {
            return $this->_access_denied();
        }
        // dd($request->all());
        $type = $request->type;
        $year = $request->year;
        $week = $request->week;
        $month = date('F Y', strtotime($request->month));

        if ($type == 'year') {

            $responses = DB::table('view_vendor_registration_report')->where('year', $year)->get();
            $responses_code_request = DB::table('view_code_request_report')->where('year', $year)->get();
            $responses_subscription = DB::table('view_subscription_report')->where('year_start', '<=', $year)->where('year_end', '>=', $year)->get();

            $total_vendors = DB::table('view_vendors_report')->where('year', '<=', $year)->count();
        } else if ($type == 'month') {
            $newYear = date('Y', strtotime($month));
            $newMonth = date('m', strtotime($month));

            $responses = DB::table('view_vendor_registration_report')->where('year', $newYear)->where('month', $newMonth)->get();
            $responses_code_request = DB::table('view_code_request_report')->where('year', $newYear)->where('month', $newMonth)->get();
            $responses_subscription = DB::table('view_subscription_report')->where('date_start', '<=', date('Y-m-t', strtotime($month)))->where('date_end', '>=', date('Y-m-t', strtotime($month)))->get();

            $total_vendors = DB::table('view_vendors_report')->where('created_at', '<=', date('Y-m-t 00:00:00', strtotime($month)))->count();
        } else if ($type == 'week') {
            $newWeek = date('YW', strtotime($week));

            $responses = DB::table('view_vendor_registration_report')->where('week', $newWeek)->get();
            $responses_code_request = DB::table('view_code_request_report')->where('week', $newWeek)->get();
            $responses_subscription = DB::table('view_subscription_report')->where('week_start', '<=', $newWeek)->where('week_end', '>=', $newWeek)->get();

            $total_vendors = DB::table('view_vendors_report')->where('week', '<=', $newWeek)->count();
        }

        $pendingCount = $responses->where('completed', 0)->count();
        $pendingApprovalCount = $responses->where('completed', 1)->whereNull('approval_1_id')->where('created_at', '>=', '2015-03-01 00:00:00')->count();
        $pendingRequest = $responses_code_request->where('status', 'pending')->count();
        $active = $responses_subscription->count();
        $unactive = abs($responses_subscription->count() - $total_vendors);
        $total = $pendingCount + $pendingApprovalCount + $pendingRequest + $active + $unactive;

        return view('reports.vendor.registration.view', compact('pendingCount', 'pendingApprovalCount', 'pendingRequest', 'active', 'unactive', 'total', 'type', 'year', 'month', 'week'));
    }
}
