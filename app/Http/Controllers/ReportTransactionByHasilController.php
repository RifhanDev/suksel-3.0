<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportTransactionByHasilController extends Controller
{
    public function index()
    {
        if (!auth()->check() || !auth()->user()->ability(['Admin'], ['Report:view:transaction_hasil'])) {
            return $this->_access_denied();
        }
        return view('reports.transaction.hasil.index');
    }

    public function view(Request $request)
    {
        if (!auth()->check() || !auth()->user()->ability(['Admin'], ['Report:view:transaction_hasil'])) {
            return $this->_access_denied();
        }
        $type = $request->type;
        $year = $request->year;
        $week = $request->week;
        $month = date('F Y', strtotime($request->month));

        if ($type == 'year') {
            $responses_purchase = DB::table('view_transaction_hasil_report')->where('year', $year)->where('hasil_code','73105')->where('status','success')->orderBy('created_at')->get();
            $responses_subscription = DB::table('view_transaction_hasil_report')->where('year', $year)->where('hasil_code','71399')->where('status','success')->orderBy('created_at')->get();
        } else if ($type == 'month') {
            $newYear = date('Y', strtotime($month));
            $newMonth = date('m', strtotime($month));
            $responses_purchase = DB::table('view_transaction_hasil_report')->where('year', $newYear)->where('month', $newMonth)->where('hasil_code','73105')->where('status','success')->orderBy('created_at')->get();
            $responses_subscription = DB::table('view_transaction_hasil_report')->where('year', $newYear)->where('month', $newMonth)->where('hasil_code','71399')->where('status','success')->orderBy('created_at')->get();
        } else if ($type == 'week') {
            $newWeek = date('YW', strtotime($week));
            $week = date('W, Y', strtotime($week));
            // dd($week);
            $responses_purchase = DB::table('view_transaction_hasil_report')->where('week', $newWeek)->where('hasil_code','73105')->where('status','success')->orderBy('created_at')->get();
            $responses_subscription  = DB::table('view_transaction_hasil_report')->where('week', $newWeek)->where('hasil_code','71399')->where('status','success')->orderBy('created_at')->get();
        }

        return view('reports.transaction.hasil.view', compact('responses_purchase','responses_subscription', 'type', 'year', 'month', 'week'));
    }
}
