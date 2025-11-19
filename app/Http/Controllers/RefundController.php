<?php

namespace App\Http\Controllers;

use Datatables;
use Carbon\Carbon;
use App\Models\Refund;
use App\Models\Upload;
use App\Models\Vendor;
use App\Traits\Helper;
use App\Models\BankList;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\RejectTemplate;
use App\Http\Controllers\Controller;

class RefundController extends Controller
{
    use Helper;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_request(Request $request)
    {

        if (!Refund::canList()) {
            return $this->_access_denied();
        }

        if ($request->ajax()) {

            if ($state = $request->state) {
                switch ($state) {
                    case 'pending_request_refund':
                        $refunds = Refund::pendingRefundRequest();
                        break;
                    case 'process_request_refund':
                        $refunds = Refund::processRefundRequest();
                        break;
                    case 'reject_request_refund':
                        $refunds = Refund::rejectRefundRequest();
                        break;
                }
            } else {
                $refunds = Refund::where('status', 3);
            }

            $refunds = $refunds->select([
                'id',
                'number',
                'created_at',
                'updated_at',
                'status',
                'amount',
            ]);
            return Datatables::of($refunds)
                ->editColumn('number', function ($refund) {
                    return $this->refundNumGenerator($refund->number);
                })
                ->editColumn('created_at', function ($refund) {
                    return Carbon::parse($refund->created_at)->format('j M Y');
                })
                ->editColumn('updated_at', function ($refund) {
                    return Carbon::parse($refund->updated_at)->format('j M Y');
                })
                ->editColumn('status', function ($refund) {
                    return $refund->refundStatus();
                })
                ->addColumn('actions', function ($refund) {
                    $actions   = [];
                    $actions[] = $refund->canShow() ? link_to_action('RefundController@show_request', 'Papar', $refund->id, ['class' => 'btn btn-xs btn-primary']) : '';
                    return implode(' ', $actions);
                })
                ->rawColumns(['number', 'created_at', 'status', 'actions'])
                ->make(true);
        }

        return view('refunds.request.index');
    }

    public function index_complaint(Request $request)
    {

        if (!Refund::isRoleBKP()) {
            return $this->_access_denied();
        }

        if ($request->ajax()) {

            if ($state = $request->state) {
                switch ($state) {
                    case 'pending_complaint_refund':
                        $refunds = Refund::pendingRefundComplaint();
                        break;
                    case 'reject_complaint_refund':
                        $refunds = Refund::rejectRefundComplaint();
                        break;
                }
            } else {
                $refunds = Refund::where('status', 3);
            }

            $refunds = $refunds->select([
                'id',
                'number',
                'created_at',
                'updated_at',
                'status',
                'amount',
            ]);
            return Datatables::of($refunds)
                ->editColumn('number', function ($refund) {
                    return $this->refundNumGenerator($refund->number);
                })
                ->editColumn('created_at', function ($refund) {
                    return Carbon::parse($refund->created_at)->format('j M Y');
                })
                ->editColumn('updated_at', function ($refund) {
                    return Carbon::parse($refund->updated_at)->format('j M Y');
                })
                ->editColumn('status', function ($refund) {
                    return $refund->refundStatus();
                })
                ->addColumn('actions', function ($refund) {
                    $actions   = [];
                    $actions[] = $refund->canShow() ? link_to_action('RefundController@show_complaint', 'Papar', $refund->id, ['class' => 'btn btn-xs btn-primary']) : '';
                    return implode(' ', $actions);
                })
                ->rawColumns(['number', 'created_at', 'status', 'actions'])
                ->make(true);
        }

        return view('refunds.complaint.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();
        // dd($user);
        $banks = BankList::get(['id', 'display_name']);

        return view('refunds.create', compact('banks'));
    }

    public function fetch_transactions(Request $request)
    {
        $vendor_id = auth()->user()->vendor_id;

        $transactions = Transaction::where('transactions.vendor_id', $vendor_id)
            ->leftJoin("refunds", "refunds.transaction_id", "=", "transactions.id")
            ->whereRaw('refunds.id is null')
            // ->where('transactions.status', 'success')
            ->select(['transactions.id', 'transactions.number', 'transactions.created_at', 'transactions.vendor_id', 'transactions.gateway_reference'])->orderby('transactions.created_at', 'desc')->paginate(10, ['*'], 'page', request()->get('page'));

        $response = array();

        foreach ($transactions as $transaction) {
            $year = date('d-m-Y', strtotime($transaction->created_at));
            $receipt = $this->receiptNumGenerator($transaction->number, $year);
            $new_receipt = ($receipt != 'old') ? $receipt : $transaction->vendor_id . '-' . $transaction->gateway_reference;
            $response[] = array(
                "id" => $transaction->id,
                "text" => date('d-m-Y h:i A', strtotime($transaction->created_at)) . ' | Nombor Resit - ' . $new_receipt,
            );
        }
        return response()->json($response);
    }

    public function get_refund_details(Request $request)
    {
        $transaction_id = $request->id;

        $transaction = Transaction::where('id', $transaction_id)->with('purchases.tender')->first();

        $response = array(
            'vendor' => $transaction->vendor->name,
            'method' => $transaction->method,
            'transaction_date' => date('d-m-Y h:i A', strtotime($transaction->created_at)),
            'amount' => $transaction->amount,
            'title' => ($transaction->type == 'purchase') ? $transaction->purchases[0]->tender->name : 'Langganan',
            'agency' => ($transaction->type == 'purchase') ? $transaction->agency->name : 'SUK Selangor'
        );

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Refund::canCreate())
            return $this->_access_denied();

        $user = auth()->user();
        $request['vendor_id'] = $user->vendor_id;
        $request['user_id'] = $user->id;
        $data = $request->except('application_letter', 'bank_statement1', 'bank_statement2', 'screenshot_problem');
        // dd($data);

        if ($user->vendor_id)
            $vendor = Vendor::findOrFail($user->vendor_id);

        $refund = new Refund;
        $refund->user()->associate(auth()->user());
        $refund->vendor()->associate($vendor);
        $refund->fill($data);

        if (!$refund->save())
            return $this->_validation_error($refund);

        return redirect('refunds/create')->with('success', $this->created_message);
    }

    public function edit($id)
    {
        $refund = Refund::with('banks', 'files')->findOrFail($id);
        if (!auth()->user()->hasRole('Vendor') && (auth()->user()->vendor_id == $refund->vendor_id))
            return $this->_access_denied();

        $banks = BankList::get(['id', 'display_name']);

        $transaction = Transaction::with('purchases.tender')->findOrFail($refund->transaction_id);

        $date = date('d-m-Y', strtotime($transaction->created_at));

        $transaction->receipt = $this->receiptNumGenerator($transaction->number, $date);
        $transaction->transaction_date = date('d-m-Y h:i A', strtotime($transaction->created_at));
        $refund->ref_no = $this->refundNumGenerator($refund->number);

        return view('refunds.edit', compact('refund', 'transaction', 'banks'));
    }

    public function update(Request $request,$refund_id)
    {
        if (!auth()->user()->hasRole('Vendor') && (auth()->user()->vendor_id == $request->vendor_id))
            return $this->_access_denied();

        $user = auth()->user();
        $request['vendor_id'] = $user->vendor_id;
        $request['user_id'] = $user->id;
        $request['status'] = 0;
        $request['rejection_template_id'] = null;
        $request['rejection_reason'] = null;
        $request['operation'] = 'updateeeeee';
        $data = $request->except('application_letter', 'bank_statement1', 'bank_statement2', 'screenshot_problem');
        

        $refund = Refund::where('id',$refund_id)->first();
        $refund->fill($data);

        if (!$refund->save())
            return $this->_validation_error($refund);

        return redirect(route('refunds.show',$refund_id))->with('success', 'Permohonan Dikemaskini');
    }

    public function pendingRefundRequestIndex()
    {
        if (!Refund::canList()) {
            return $this->_access_denied();
        }

        return view('refunds.request.index', [
            'subtitle'        => 'Permohonan Baru',
            'status' => 'pending_request_refund',
            'date_col' => 'Tarikh Kemaskini'
        ]);
    }

    public function pendingRefundComplaintIndex()
    {
        if (!Refund::isRoleBKP()) {
            return $this->_access_denied();
        }

        return view('refunds.complaint.index', [
            'subtitle'        => 'Aduan Baru',
            'status' => 'pending_complaint_refund',
            'date_col' => 'Tarikh Kemaskini'
        ]);
    }

    public function processRefundRequestIndex()
    {
        if (!Refund::canList()) {
            return $this->_access_denied();
        }

        return view('refunds.request.index', [
            'subtitle'        => 'Permohonan Dalam Proses',
            'status' => 'process_request_refund',
            'date_col' => 'Tarikh Diluluskan'
        ]);
    }

    public function rejectRefundRequestIndex()
    {
        if (!Refund::canList()) {
            return $this->_access_denied();
        }

        return view('refunds.request.index', [
            'subtitle'        => 'Permohonan Ditolak',
            'status' => 'reject_request_refund',
            'date_col' => 'Tarikh Ditolak'
        ]);
    }

    public function rejectRefundComplaintIndex()
    {
        if (!Refund::isRoleBKP()) {
            return $this->_access_denied();
        }

        return view('refunds.complaint.index', [
            'subtitle'        => 'Aduan Ditolak',
            'status' => 'reject_complaint_refund',
            'date_col' => 'Tarikh Ditolak'
        ]);
    }

    public function show($id)
    {
        $refund = Refund::with('banks', 'files')->findOrFail($id);

        if (!$refund->canShow())
            return $this->_access_denied();

        $transaction = Transaction::with('purchases.tender')->findOrFail($refund->transaction_id);
        $templates = RejectTemplate::where('applicable_1', 1)->get(['id', 'title', 'content']);

        $date = date('d-m-Y', strtotime($transaction->created_at));

        $transaction->receipt = $this->receiptNumGenerator($transaction->number, $date);
        $transaction->transaction_date = date('d-m-Y h:i A', strtotime($transaction->created_at));
        $refund->ref_no = $this->refundNumGenerator($refund->number);
        $refund->page = 'vendor';

        return view('refunds.show', compact('refund', 'transaction', 'templates'));
    }

    public function show_request($id)
    {
        $refund = Refund::with('banks', 'files')->findOrFail($id);

        if (!$refund->canShow())
            return $this->_access_denied();

        $transaction = Transaction::with('purchases.tender')->findOrFail($refund->transaction_id);
        $templates = RejectTemplate::where('applicable_1', 1)->get(['id', 'title', 'content']);

        $date = date('d-m-Y', strtotime($transaction->created_at));

        $transaction->receipt = $this->receiptNumGenerator($transaction->number, $date);
        $transaction->transaction_date = date('d-m-Y h:i A', strtotime($transaction->created_at));
        $refund->ref_no = $this->refundNumGenerator($refund->number);
        $refund->page = 'request';

        return view('refunds.show', compact('refund', 'transaction', 'templates'));
    }

    public function show_complaint($id)
    {
        $refund = Refund::with('banks', 'files')->findOrFail($id);

        if (!$refund->canShow())
            return $this->_access_denied();

        $transaction = Transaction::with('purchases.tender')->findOrFail($refund->transaction_id);
        $templates = RejectTemplate::where('applicable_1', 1)->get(['id', 'title', 'content']);

        $date = date('d-m-Y', strtotime($transaction->created_at));

        $transaction->receipt = $this->receiptNumGenerator($transaction->number, $date);
        $transaction->transaction_date = date('d-m-Y h:i A', strtotime($transaction->created_at));
        $refund->ref_no = $this->refundNumGenerator($refund->number);
        $refund->page = 'complaint';

        return view('refunds.show', compact('refund', 'transaction', 'templates'));
    }

    public function reject_request(Request $request, $refund_id)
    {
        if (!Refund::canApprove())
            return $this->_access_denied();

        $template = null;
        $rejection_reason = null;
        if ($request->template != null) {
            $template = json_encode($request->template);
        }
        if ($request->reason != '') {
            $rejection_reason = $request->reason;
        }
        $refund = Refund::findOrFail($refund_id);
        $refund->status = 2;
        $refund->rejection_reason = $rejection_reason;
        $refund->rejection_template_id = $template;
        $refund->save();

        // email function added by zayid 29 apr 2023
        $to			= trim($refund->user->email);
        $subject 	= 'Permohonan Pemulangan Semula Ditolak';
        $send_status = $this->sendMail("html", $to, $subject, "", "refunds.emails.request.rejected", ['refund'=>$refund,'refund_num' => $this->refundNumGenerator($refund->number)]);

        session()->flash('info', 'Permohonan Ditolak.');
        return 'true';
    }
    public function approve_request($refund_id)
    {
        if (!Refund::canApprove())
            return $this->_access_denied();

        $refund = Refund::findOrFail($refund_id);

        $refund->status = 1;
        $refund->rejection_reason = null;
        $refund->rejection_template_id = null;
        $refund->save();

        // email function added by zayid 29 apr 2023
        $to			= trim($refund->user->email);
        $subject 	= 'Permohonan Pemulangan Semula Diluluskan Oleh BPM';
        $send_status = $this->sendMail("html", $to, $subject, "", "refunds.emails.request.approved", ['refund'=>$refund,'refund_num' => $this->refundNumGenerator($refund->number)]);

        return redirect('refunds/request/' . $refund_id . '/show')->with('success', 'Permohonan Diluluskan.');
    }

    public function reject_complaint(Request $request, $refund_id)
    {
        if (!Refund::canApprove())
            return $this->_access_denied();

        $template = null;
        $rejection_reason = null;
        if ($request->template != null) {
            $template = json_encode($request->template);
        }
        if ($request->reason != '') {
            $rejection_reason = $request->reason;
        }
        $refund = Refund::findOrFail($refund_id);
        $refund->status = 4;
        $refund->rejection_reason = $rejection_reason;
        $refund->rejection_template_id = $template;
        $refund->save();

        // email function added by zayid 29 apr 2023
        $to			= trim($refund->user->email);
        $subject 	= 'Permohonan Pemulangan Semula Ditolak';
        $send_status = $this->sendMail("html", $to, $subject, "", "refunds.emails.complaint.rejected", ['refund'=>$refund,'refund_num' => $this->refundNumGenerator($refund->number)]);

        session()->flash('info', 'Aduan Ditolak.');
        return 'true';
    }
    public function approve_complaint($refund_id)
    {
        if (!Refund::canApprove())
            return $this->_access_denied();

        $refund = Refund::findOrFail($refund_id);

        $refund->status = 3;
        $refund->rejection_reason = null;
        $refund->rejection_template_id = null;
        $refund->save();

        // email function added by zayid 29 apr 2023
        $to			= trim($refund->user->email);
        $subject 	= 'Permohonan Pemulangan Semula Diluluskan';
        $send_status = $this->sendMail("html", $to, $subject, "", "refunds.emails.complaint.approved", ['refund'=>$refund,'refund_num' => $this->refundNumGenerator($refund->number)]);

        return redirect('refunds/complaint/' . $refund_id . '/show')->with('success', 'Bukti Aduan Diterima');
    }
}
