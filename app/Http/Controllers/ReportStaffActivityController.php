<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\CodeRequest;
use Illuminate\Http\Request;
use App\Models\TenderHistory;
use App\Models\VendorHistory;

class ReportStaffActivityController extends Controller
{
    protected $exclude_roles = [
        'Vendor'
    ];

    protected $tender_activities = [
        'create',
        'edit',
        'delete',
        'publish',
        'publish-prices',
        'publish-winner',
        'unpublish',
        'unpublish-prices',
        'unpublish-winner',
        'update-invites',
        'update-vendors'
    ];

    protected $vendor_activities = [
        'edit',
        'delete',
        'blacklist',
        'edit-2',
        'edit-3',
        'approve',
        'reject'
    ];

    public function index()
    {
        if (!auth()->check() || !auth()->user()->ability(['Admin'], ['Report:view:staff_activity'])) {
            return $this->_access_denied();
        }
        return view('reports.staff.activity.index');
    }

    public function view(Request $request)
    {
        if (!auth()->check() || !auth()->user()->ability(['Admin'], ['Report:view:staff_activity'])) {
            return $this->_access_denied();
        }
        $type = $request->type;
        $year = $request->year;
        $month = date('Y-m-d', strtotime($request->month));

        $that = $this;
        $users = User::whereHas('roles', function ($query) use ($that) {
            return $query->whereNotIn('name', $that->exclude_roles);
        })->pluck('id')->toArray();

        if ($type == 'year') {
            $date = $this->fullYearDateGenerate($year);
        } else if ($type == 'month') {
            $date = $this->fullMonthDateGenerate($month);
        }

        $start = $date['start_date'];
        $end = $date['end_date'];

        return view('reports.staff.activity.view', [
            'data'              => $this->query($users, $start, $end),
            'tender_activities' => $this->tender_activities,
            'vendor_activities' => $this->vendor_activities,
            'type'              => $type,
            'year'              => $year,
            'month'             => date('F Y',strtotime($request->month))
        ]);
    }

    public function query($user_ids, $start, $end)
    {
        $users  = User::whereIn('id', $user_ids)->get();
        $data   = [];

        foreach ($users as $user) {
            $activities = [];

            foreach ($this->tender_activities as $activity) {
                $activities[$activity] = TenderHistory::whereUserId($user->id)
                    ->whereAction($activity)
                    ->whereBetween('created_at', [$start, $end])
                    ->count();
            }

            foreach ($this->vendor_activities as $activity) {
                $activities[$activity] = VendorHistory::whereUserId($user->id)
                    ->whereAction($activity)
                    ->whereBetween('created_at', [$start, $end])
                    ->count();
            }

            $activities['change-request'] = CodeRequest::where('approval_1_id', $user->id)
                ->whereBetween('created_at', [$start, $end])
                ->count();

            $activities['total'] = array_sum($activities);
            $data[$user->name] = $activities;
        }

        $data['Jumlah'] = [];

        foreach ($this->tender_activities as $activity) {
            $data['Jumlah'][$activity]  = TenderHistory::whereAction($activity)
                ->whereIn('user_id', $user_ids)
                ->whereBetween('created_at', [$start, $end])
                ->count();
        }

        foreach ($this->vendor_activities as $activity) {
            $data['Jumlah'][$activity] = VendorHistory::whereIn('user_id', $user_ids)
                ->whereAction($activity)
                ->whereBetween('created_at', [$start, $end])
                ->count();
        }

        $data['Jumlah']['change-request'] = CodeRequest::whereIn('approval_1_id', $user_ids)
            ->whereBetween('created_at', [$start, $end])
            ->count();

        $data['Jumlah']['total'] = TenderHistory::whereIn('user_id', $user_ids)->whereBetween('created_at', [$start, $end])->count();

        return [$users, $data];
    }

    public function fullMonthDateGenerate($month)
    {
        $date = [];

        $date['start_date'] = date('Y-m-01 00:00:00', strtotime($month));
        $date['end_date'] = date('Y-m-t 12:59:59', strtotime($month));

        return $date;
    }

    public function fullYearDateGenerate($year)
    {
        $date = [];

        $date['start_date'] = date('Y-m-d 00:00:00', strtotime($year . '-01-01'));
        $date['end_date'] = date('Y-m-d 12:59:59', strtotime($year . '-12-31'));

        return $date;
    }
}
