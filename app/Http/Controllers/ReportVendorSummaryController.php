<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportVendorSummaryController extends Controller
{
    public function index($year, $vendor_id)
    {
        if (!auth()->check())
            return $this->_access_denied();

        if (Auth::user()->hasRole('Vendor')) {
            if (Auth::user()->vendor_id != $vendor_id)
                return $this->_access_denied();
        } else if (!auth()->user()->ability(['Admin'], [])) {
            return $this->_access_denied();
        }

        $total_transaction = Transaction::where('vendor_id', $vendor_id)->where('status', 'success')->groupBy('vendor_id')->count();
        $total_sum = Transaction::selectRaw('sum(amount) as total')->where('vendor_id', $vendor_id)->where('status', 'success')->groupBy('vendor_id')->first();
        $total_transaction_yearly = Transaction::where('vendor_id', $vendor_id)->where('status', 'success')->whereYear('created_at', $year)->groupBy('vendor_id')->count();

        $lists = Transaction::where('vendor_id', $vendor_id)->whereYear('created_at', $year)->where('status', 'success')->with('purchases.tender')->get();
        // dd($lists);
        return view('reports.vendor.summary.index', compact('vendor_id', 'year', 'lists', 'total_transaction', 'total_sum', 'total_transaction_yearly'));
    }
}
