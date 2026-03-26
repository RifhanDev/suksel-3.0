<?php

namespace App\Http\Controllers;

use Datatables;
use Carbon\Carbon;
use App\Models\Complaint;
use App\User;
use App\Traits\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ComplaintController extends Controller
{
    use Helper;
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
        // Add user_id if user is authenticated
        if (auth()->check()) {
            $data['user_id'] = auth()->user()->id;
        }
        // dd($data);
        $complaint = new Complaint;
        $complaint->fill($data);

        if (!$complaint->save())
            return $this->_validation_error($complaint);

        // Send email notification to all admin users
        $this->sendEmailNotificationToAdmins($complaint);

        // Redirect authenticated users to their complaints list, others to create form
        if (auth()->check()) {
            return redirect()->route('my.aduan.index')->with('success', 'Aduan telah dihantar');
        }

        return redirect()->route('aduan.create')->with('success', 'Aduan telah dihantar');
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

    /**
     * Reply to a complaint and send email to user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reply(Request $request, $id)
    {
        if (!Complaint::canApprove()) {
            return $this->_access_denied();
        }

        $validator = Validator::make($request->all(), [
            'admin_reply' => 'required|string|max:4000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $complaint = Complaint::findOrFail($id);

        $complaint->admin_reply = $request->admin_reply;
        $complaint->replied_by = auth()->user()->id;
        $complaint->replied_at = now();
        $complaint->save();

        // Send reply email to user
        $this->sendReplyEmailToUser($complaint);

        return redirect('aduan/' . $id)->with('success', 'Balasan telah dihantar kepada pengadu.');
    }

    /**
     * Send reply email to the complaint user
     */
    protected function sendReplyEmailToUser($complaint)
    {
        try {
            if (empty($complaint->email)) {
                Log::warning('Complaint email is empty, cannot send reply');
                return;
            }

            $to = trim($complaint->email);
            $subject = 'Balasan Aduan Anda - ' . $complaint->subject;

            $this->sendMail(
                "html",
                $to,
                $subject,
                "",
                "complaint.emails.reply",
                ['complaint' => $complaint]
            );

            Log::info('Complaint reply email sent to: ' . $to);
        } catch (\Exception $e) {
            Log::error('Failed to send complaint reply email: ' . $e->getMessage());
            // Don't fail the reply if email fails
        }
    }

    /**
     * Display user's own complaints.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function myComplaints(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Sila daftar masuk untuk melihat aduan anda.');
        }

        if ($request->ajax()) {
            $complaints = Complaint::where('user_id', auth()->user()->id);

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
                    $actions[] = link_to_route('my.aduan.show', 'Lihat Aduan', $complaint->id, ['class' => 'btn btn-xs btn-primary']);
                    $actions[] = '</div>';
                    return implode(' ', $actions);
                })
                ->removeColumn('id')
                ->rawColumns(['subject', 'content', 'status', 'created_at', 'actions'])
                ->make();
        }

        return view('complaint.my-index');
    }

    /**
     * Display user's own complaint details.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function myComplaintShow($id)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Sila daftar masuk untuk melihat aduan anda.');
        }

        $complaint = Complaint::findOrFail($id);

        // Ensure user can only view their own complaint
        if ($complaint->user_id !== auth()->user()->id) {
            return $this->_access_denied();
        }

        return view('complaint.my-show', compact('complaint'));
    }

    /**
     * Send email notification to all admin users
     */
    protected function sendEmailNotificationToAdmins($complaint)
    {
        try {
            // Get all users with Admin role
            $adminUsers = User::whereHas('roles', function ($query) {
                $query->where('name', 'Admin');
            })
                ->where('confirmed', 1)
                ->whereNotNull('email')
                ->where('email', '!=', 'anonymous')
                ->get();

            if ($adminUsers->isEmpty()) {
                Log::warning('No admin users found to send complaint notification');
                return;
            }

            // Send email to each admin
            $emailsSent = 0;
            $emailsFailed = 0;
            foreach ($adminUsers as $admin) {
                if (filter_var(trim($admin->email), FILTER_VALIDATE_EMAIL)) {
                    $to = trim($admin->email);
                    $subject = 'Aduan Baru Diterima - ' . $complaint->subject;

                    $result = $this->sendMail(
                        "html",
                        $to,
                        $subject,
                        "",
                        "complaint.emails.new-complaint",
                        ['complaint' => $complaint]
                    );

                    if ($result === "Email send to queue" || strpos($result, "Email") !== false) {
                        $emailsSent++;
                        Log::info("Complaint email queued for admin: {$to}");
                    } else {
                        $emailsFailed++;
                        Log::warning("Failed to queue complaint email for admin {$to}: {$result}");
                    }
                }
            }

            Log::info('Complaint notification emails: ' . $emailsSent . ' queued, ' . $emailsFailed . ' failed out of ' . $adminUsers->count() . ' admin(s)');
        } catch (\Exception $e) {
            Log::error('Failed to send complaint notification emails: ' . $e->getMessage());
            // Don't fail the complaint submission if email fails
        }
    }
}
