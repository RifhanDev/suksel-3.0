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
                    $svgClock = '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>';
                    $svgCheckInProgress = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M10 20.777a9 9 0 0 1-2.48-.969M14 3.223a9.003 9.003 0 0 1 0 17.554m-9.421-3.684a9 9 0 0 1-1.227-2.592M3.124 10.5c.16-.95.468-1.85.9-2.675l.169-.305m2.714-2.941A9 9 0 0 1 10 3.223"/><path d="m9 12l2 2l4-4"/></g></svg>';
                    $svgCheckCircle = '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>';
                    $svgX = '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>';
                    $svgNew = '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>';

                    switch ($complaint->status) {
                        case 0:
                            $badge = '<span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill small fw-semibold" style="background:#dbeafe;color:#1d4ed8;">' . $svgNew . ' Baru</span>';
                            break;
                        case 1:
                            $badge = '<span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill small fw-semibold" style="background:#ccfbf1;color:#0f766e;">' . $svgCheckInProgress . ' Ambil Maklum</span>';
                            break;
                        case 2:
                            $badge = '<span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill small fw-semibold" style="background:#fef3c7;color:#b45309;">' . $svgClock . ' Dalam Tindakan</span>';
                            break;
                        case 3:
                            $badge = '<span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill small fw-semibold" style="background:#dcfce7;color:#15803d;">' . $svgCheckCircle . ' Selesai</span>';
                            break;
                        case 4:
                            $badge = '<span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill small fw-semibold" style="background:#fee2e2;color:#b91c1c;">' . $svgX . ' Ditolak</span>';
                            break;
                        default:
                            $badge = '<span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill small fw-semibold" style="background:#f1f5f9;color:#64748b;">-</span>';
                            break;
                    }
                    return '<div class="text-center">' . $badge . '</div>';
                })
                ->addColumn('actions', function ($complaint) {

                    $actions   = [];
                    $actions[] = link_to_route('aduan.show', 'Lihat Aduan', $complaint->id, ['class' => 'btn btn-sm btn-primary rounded-8 px-3']);
                    return '<div class="d-flex gap-2 justify-content-center">' . implode('', $actions) . '</div>';
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
