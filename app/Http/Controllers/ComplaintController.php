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
