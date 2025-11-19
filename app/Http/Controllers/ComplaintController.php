<?php

namespace App\Http\Controllers;

use Datatables;
use Carbon\Carbon;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if (!Complaint::canList())
            return $this->_access_denied();

        if ($request->ajax()) {
            $complaints = Complaint::select('*');

            return Datatables::of($complaints)
                ->editColumn('content', function ($complaint) {
                    return (strlen($complaint->content) > 70) ? substr($complaint->content, 0, 70) . '...' : $complaint->content;
                })
                ->editColumn('created_at', function ($complaint) {
                    return Carbon::parse($complaint->created_at)->format('j M Y h:i a');
                })
                ->editColumn('status', function ($complaint) {
                    return $complaint->complaintStatus();
                })
                ->addColumn('actions', function ($complaint) {

                    $actions   = [];
                    $actions[] = '<div class="btn-group">';
                    $actions[] = link_to_route('aduan.show', 'Lihat Aduan', $complaint->id, ['class' => 'btn btn-xs btn-primary']);
                    $actions[] = '</div>';
                    return implode(' ', $actions);
                })
                ->removeColumn('id')
                ->rawColumns(['subject', 'content', 'status', 'created_at', 'actions'])
                ->make();
        }

        return view('complaint.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('complaint.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Your other validation rules here
            'g-recaptcha-response' => 'required|recaptcha',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Tidak berjaya dihantar. Sila Tanda reCAPTCHA')
                ->withInput();
        }

        $data = $request->all();
        // dd($data);
        $complaint = new Complaint;
        $complaint->fill($data);

        if (!$complaint->save())
            return $this->_validation_error($complaint);

        return redirect('aduan')->with('success', 'Aduan telah dihantar');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Complaint  $complaint
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        if (!Complaint::canShow())
            return $this->_access_denied();

        $complaint = Complaint::findOrFail($id);

        return view('complaint.show', compact('complaint'));
    }

    public function updateStatus($id, $status)
    {
        if (!Complaint::canApprove() || !in_array($status, [1, 2, 3, 4]))
            return $this->_access_denied();

        $complaint = Complaint::findOrFail($id);

        $complaint->status = $status;
        $complaint->save();

        return redirect('aduan/' . $id)->with('success', 'Status telah dikemaskini');
    }
}
