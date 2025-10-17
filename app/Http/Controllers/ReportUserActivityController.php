<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Excel;
use App\Exports\UserActivity;
use App\User;
use App\TenderHistory;
use App\CodeRequest;
use App\VendorHistory;

class ReportUserActivityController extends Controller
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
		'update-vendors',
		'exception'
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
		$that = $this;
		$select_users = User::whereHas('roles', function ($query) use ($that) {
			return $query->whereNotIn('name', $that->exclude_roles);
		})->get();

		return view('reports.user.activity.index', compact('select_users'));
	}

	public function view(Request $request)
	{

		$users      = $request->input('users', []);
		$date_start = $request->input('date_start');
		$date_end   = $request->input('date_end');

		// dd($users);

		$start  = Carbon::createFromFormat('d/m/Y', $date_start);
		$end    = Carbon::createFromFormat('d/m/Y', $date_end);

		return view('reports.user.activity.view', [
			'data'              => $this->query($users, $start, $end),
			'tender_activities' => $this->tender_activities,
			'vendor_activities' => $this->vendor_activities,
			'date_start'        => $date_start,
			'date_end'          => $date_end
		]);
	}

	public function excel(Request $request)
	{

		$users      = $request->input('users', []);
		$date_start = $request->input('date_start');
		$date_end   = $request->input('date_end');

		// dd($users);

		$start  = Carbon::createFromFormat('d/m/Y', $date_start);
		$end    = Carbon::createFromFormat('d/m/Y', $date_end);
		$data   = $this->query($users, $start, $end);
		$tender_activities  = $this->tender_activities;
		$vendor_activities  = $this->vendor_activities;

		return Excel::download(new UserActivity($data, $tender_activities, $vendor_activities), 'Produktivi Staff.xlsx');
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
}
