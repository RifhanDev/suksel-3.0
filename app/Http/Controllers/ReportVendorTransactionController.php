<?php

namespace App\Http\Controllers;

use App\Traits\Helper;
use Illuminate\Http\Request;
use App\Models\OrganizationUnit;
use Illuminate\Support\Facades\DB;

class ReportVendorTransactionController extends Controller
{
    use Helper;

    public function index()
    {
        if (!auth()->check() || !auth()->user()->ability(['Admin'], ['Report:view:vendor_transaction'])) {
            return $this->_access_denied();
        }
        $agencies = OrganizationUnit::get(['id', 'name']);

        return view('reports.vendor.transaction.index', compact('agencies'));
    }

    /* public function view(Request $request)
    {
        $agency_id = $request->agency;
        $type = $request->type;
        $year = $request->year;
        $week = $request->week;
        $month = date('F Y', strtotime($request->month));

        if ($type == 'year') {
            $responses = DB::table('view_vendor_transaction_report')->where('year', $year)->where('organization_unit_id', $agency_id)->orderBy('created_at')->get();
            // $responses = DB::table('view_vendor_transaction_report')->where('transaction_id', '1001273770')->orderBy('created_at')->get();
        } else if ($type == 'month') {
            $newYear = date('Y', strtotime($month));
            $newMonth = date('m', strtotime($month));
            $responses = DB::table('view_vendor_transaction_report')->where('year', $newYear)->where('month', $newMonth)->where('organization_unit_id', $agency_id)->orderBy('created_at')->get();
        } else if ($type == 'week') {
            $newWeek = date('YW', strtotime($week));
            $week = date('W, Y', strtotime($week));
            // dd($week);
            $responses = DB::table('view_vendor_transaction_report')->where('week', $newWeek)->where('organization_unit_id', $agency_id)->orderBy('created_at')->take(50)->get();
        }

        // foreach($responses as $response)
        // {
        //     $response->receipt = $this->receiptNumGenerator($response->number,date('d-m-Y',strtotime($response->created_at)));
        //     $response->details = DB::table('view_tender_vendor_report')->where('transaction_id',$response->transaction_id)->get();
        // }
        // dd($responses);

        $agency = OrganizationUnit::select('name')->where('id', $agency_id)->first();

        return view('reports.vendor.transaction.view', compact('responses', 'agency', 'type', 'year', 'month', 'week'));
    } */

    public function view(Request $request)
    {
        if (!auth()->check() || !auth()->user()->ability(['Admin'], ['Report:view:vendor_transaction'])) {
            return $this->_access_denied();
        }
        set_time_limit(0);
        $agency_id = $request->agency;
        $type = $request->type;
        $year = $request->year;
        $week = $request->week;
        $month = date('F Y', strtotime($request->month));

        $query = DB::table('view_vendor_transaction_report')
            ->where('organization_unit_id', $agency_id)
            ->orderBy('created_at');

        if ($type === 'year') {
            $query->where('year', $year);
        } elseif ($type === 'month') {
            $newYear = date('Y', strtotime($month));
            $newMonth = date('m', strtotime($month));
            $query->where('year', $newYear)->where('month', $newMonth);
        } elseif ($type === 'week') {
            $newWeek = date('YW', strtotime($week));
            $query->where('week', $newWeek);
        }

        $responses = $query->get();
        $responseIds = $responses->pluck('transaction_id')->toArray();

        $responseDetails = DB::table('view_tender_vendor_report')
            ->whereIn('transaction_id', $responseIds)
            ->get();

        /* $responseDetails = DB::table('view_tender_vendor_report')
            ->whereIn('transaction_id', $responseIds)
            ->get()
            ->map(function ($item) {
                $gatewayMessage = $item->gateway_message;
                $pattern = '/fpx_buyerBankId:\s*([^|]+)/';
                preg_match($pattern, $gatewayMessage, $matches);

                if (isset($matches[1])) {
                    $buyerBankId = trim($matches[1]);
                    $item->fpx_buyerBankId = $buyerBankId;
                } else {
                    $item->fpx_buyerBankId = null;
                }

                return $item;
            }); */

        $responses = $responses->map(function ($response) use ($responseDetails) {
            $response->receipt = $this->receiptNumGenerator($response->number, date('d-m-Y', strtotime($response->created_at)));
            /* $response->details = $responseDetails->where('transaction_id', $response->transaction_id)->values(); */
            $response->details = $responseDetails->where('transaction_id', $response->transaction_id)->map(function ($item) {
                $gatewayMessage = $item->gateway_message;
                $pattern = '/fpx_buyerBankId:\s*([^|]+)/';
                preg_match($pattern, $gatewayMessage, $matches);

                if (isset($matches[1])) {
                    $buyerBankId = trim($matches[1]);
                    $item->fpx_buyerBankId = $this->fpxBuyerBankIdDictionary($buyerBankId);
                } else {
                    $item->fpx_buyerBankId = 'null';
                }

                return $item;
            })->values();

            return $response;
        });

        $agencyName = OrganizationUnit::select('name')->where('id', $agency_id)->value('name');

        return view('reports.vendor.transaction.view', compact('responses', 'agencyName', 'type', 'year', 'month', 'week'));
    }

    public function fpxBuyerBankIdDictionary($fpx_buyerBankId)
    {
        $b2b = [
            'ABB0235', 'ABMB0213', 'AGRO02', 'AMBB0208', 'BIMB0340', 'BMMB0342', 'BNP003', 'BCBB0235', 'CIT0218', 'DBB0199', 'HLB0224', 'HSBC0223', 'BKRM0602', 'KFH0346', 'MBB0228', 'OCBC0229', 'PBB0233',
            'PBB0234', 'RHB0218', 'SCB0215', 'UOB0228'
        ];

        $b2c = [
            'ABB0233', 'ABMB0212', 'AGRO01', 'AMBB0209', 'BIMB0340', 'BMMB0341', 'BKRM0602', 'BOCM01', 'BSN0601', 'BCBB0235', 'HLB0224', 'HSBC0223', 'KFH0346', 'MBB0228',
            'MB2U0227', 'OCBC0229', 'PBB0233', 'RHB0218', 'SCB0216', 'UOB0226'
        ];

        if (in_array($fpx_buyerBankId, $b2b)) {
            return 'B2B';
        } elseif (in_array($fpx_buyerBankId, $b2c)) {
            return 'B2C';
        }

        // Default case, return null or any other value as needed
        return $fpx_buyerBankId;
    }
}
