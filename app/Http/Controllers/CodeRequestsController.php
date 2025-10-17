<?php

namespace App\Http\Controllers;

use Mail;
use App\Vendor;
use Datatables;
use App\Approval;
use Carbon\Carbon;
use App\CodeRequest;
use App\Models\RefState;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use App\Models\RejectTemplate;
use Illuminate\Support\Facades\Input;


class CodeRequestsController extends Controller
{
	public function index(Request $request, $vendor = null)
	{

		$changes = null;

		if (!CodeRequest::canList())
			return $this->_access_denied();

		if ($vendor != null)
			$vendor = Vendor::findOrFail($vendor);

		if ($request->ajax()) {
			$changes = CodeRequest::join('vendors', 'vendors.id', '=', 'code_requests.vendor_id');

			if (isset($vendor)) {
				$changes = $changes->where('vendor_id', $vendor->id);
			} else {
				$changes = $changes->whereStatus('pending');
			}

			// // if($request->state) {
			// 	// $changes = $changes->whereStatus($request->state);
			// // }
			// $changes = $changes->whereStatus('pending');


			$changes = $changes->select([
				'code_requests.id',
				'vendors.name',
				'code_requests.type',
				'code_requests.created_at',
				'code_requests.approval_1_id',
				'code_requests.status',
				'code_requests.rejection_reason',
				'code_requests.rejection_template_id',
				'code_requests.vendor_id'
			]);


			$datatable = Datatables::of($changes);

			if (isset($vendor)) {
				$datatable = $datatable->removeColumn('vendors.name');
			}

			$templates = RejectTemplate::get([
				'id', 'title', 'content'
			]);

			$datatable = $datatable->editColumn('status', function ($change) {
				return CodeRequest::$statuses[$change->status] ?? '';
			})
				->editColumn('type', function ($change) {
					return CodeRequest::$types[$change->type] ?? '';
				})
				->editColumn('created_at', function ($change) {
					return Carbon::parse($change->created_at)->format('j M Y H:i:s');
				})
				->editColumn('approval_1_id', function ($change) use ($templates) {
					if (!is_null($change->rejection_reason) || !is_null($change->rejection_template_id)) {
						$str_remark = '';
						$str_template = '';
						if (!is_null($change->rejection_reason)) {
							$str_remark = "Catatan : {$change->rejection_reason}";
						}
						if (!is_null($change->rejection_template_id)) {
							$str_template .= "<ol>";
							foreach (json_decode($change->rejection_template_id, true) as $reject_id) {
								foreach ($templates as $template) {
									if ($template['id'] == $reject_id) {
										$str_template .= '<li style="text-decoration: underline;">' . $template['title'] . '</li>';
										$str_template .= $template['content'];
									}
								}
							}
							$str_template .= "</ol>";
						}
						$string = "Ditolak kerana:- </br>" . $str_remark . $str_template;
					} elseif (is_null($change->approval_1_id)) {
						$string = '<i class="glyphicon glyphicon-remove"></i>';
					} else {
						$string = Carbon::parse(Approval::find($change->approval_1_id)->created_at)->format('j M Y');
					}

					return $string;
				});

			return $datatable
				->addColumn('actions', function ($change) use ($vendor) {
					$actions    = [];
					$actions[]  = '<div class="btn-group">';

					if ($vendor) {
						$actions[] = $change->canShow() ? link_to_route('vendor.requests.show', 'Papar', [$change->vendor, $change->id], ['class' => 'btn btn-xs btn-primary']) : '';
					} else {
						$actions[] = $change->canShow() ? link_to_route('requests.showAll', 'Papar', $change->id, ['class' => 'btn btn-xs btn-primary']) : '';
					}

					if (!$vendor && !auth()->user()->hasRole('Vendor'))
						$actions[]  = link_to_route('vendors.show', 'Maklumat Syarikat', $change->vendor, ['class' => 'btn btn-xs btn-warning']);

					$actions[] = '</div>';
					return implode(' ', $actions);
				})
				->removeColumn('id')
				->removeColumn('rejection_reason')
				->removeColumn('rejection_template_id')
				->rawColumns(['name', 'type', 'created_at', 'approval_1_id', 'status', 'actions'])
				->make();
		}

		$ajax_url = isset($vendor) ? route('vendor.requests.index', [$vendor->id]) : route('requests.index');
		return view('coderequests.index', compact('vendor', 'changes', 'ajax_url'));
	}

	public function create(Request $request, $vendor_id)
	{
		$types  = array_keys(CodeRequest::$types);
		$type   = $request->type;

		$country_states = RefState::where('display_status', 1)->get();

		if (!CodeRequest::canCreate() || !in_array($type, $types))
			return $this->_access_denied();

		if ($vendor_id)
			$vendor = Vendor::findOrFail($vendor_id);

		if (!CodeRequest::canCreateFor($vendor->id, $type))
			return $this->_access_denied();

		return view('coderequests.' . $type, compact('vendor', 'type', 'country_states'));
	}

	public function store(Request $request, $vendor_id)
	{
		$types  = array_keys(CodeRequest::$types);
		$type   = $request->type;

		if (!CodeRequest::canCreate() || !in_array($type, $types))
			return $this->_access_denied();

		if ($vendor_id)
			$vendor = Vendor::findOrFail($vendor_id);

		if (!CodeRequest::canCreateFor($vendor->id, $type))
			return $this->_access_denied();

		$request_code = new CodeRequest;
		$request_code->user()->associate(auth()->user());
		$request_code->vendor()->associate($vendor);

		$data           = $request->except('sijil_mof_bumiputera', 'sijil_mof', 'sijil_cidb', 'sijil_cidb_bumiputera');
		$data['type']   = $type;

		$request_code->processData($data);
		if (!$request_code->save())
			return $this->_validation_error($request_code);

		if ($request->ajax())
			return Response::json($request_code, 201);

		if (isset($vendor)) {
			$redirect = redirect('vendor/' . $vendor->id . '/requests');
		} else {
			$redirect = redirect('requests');
		}

		return $redirect->with('success', $this->created_message);
	}

	public function show($vendor_id, $request_id)
	{


		if ($vendor_id)
			$vendor = Vendor::findOrFail($vendor_id);

		$request = CodeRequest::findOrFail($request_id);

		if (!$request->canShow())
			return $this->_access_denied();

		$templates = RejectTemplate::where('applicable_0', 1)->get([
			'id', 'title', 'content'
		]);

		return view('coderequests.show', compact('vendor', 'request', 'templates'));
	}

	public function showAll($request_id)
	{

		$vendor = null;
		$request = CodeRequest::findOrFail($request_id);

		if (!$request->canShow())
			return $this->_access_denied();

		$templates = RejectTemplate::where('applicable_0', 1)->get([
			'id', 'title', 'content'
		]);

		return view('coderequests.show', compact('vendor', 'request', 'templates'));
	}

	public function approve_vendor($vendor_id, $request_id)
	{
		if ($vendor_id)
			$vendor = Vendor::findOrFail($vendor_id);

		$request = CodeRequest::findOrFail($request_id);

		if (!$request->canProcess() || $request->status != 'pending')
			$this->_access_denied();

		$approval          = new Approval;
		$approval->user_id = auth()->user()->id;
		$approval->save();

		$request->approval_1_id = $approval->id;
		$request->status        = 'approved';
		$request->save();

		$request->updateData();

		// Mail::send('coderequests.emails.approved', ['request' => $request], function ($message) use ($request) {
		// 	$message->to(trim($request->vendor->user->email));
		// 	$message->subject('Perubahan Maklumat Diluluskan');
		// });

		$to			= trim($request->vendor->user->email);
		$subject 	= 'Perubahan Maklumat Diluluskan';
		$send_status = $this->sendMail("html", $to, $subject, "", "coderequests.emails.approved", ['request' => $request]);


		if ($request->type == 'email') {
			// Mail::send('vendors.emails.change_email', ['vendor' => $request->vendor, 'user' => $request->vendor->user], function ($message) use ($request) {
			// 	$message->to(trim($request->data['email']));
			// 	$message->subject('Pengesahan Alamat Emel');
			// });

			$to2			= trim($request->data['email']);
			$subject2		= 'Pengesahan Alamat Emel';
			$send_status 	= $this->sendMail("html", $to2, $subject2, "", "vendors.emails.change_email", ['vendor' => $request->vendor, 'user' => $request->vendor->user]);
		}

		return redirect('vendor/' . $vendor->id . '/requests')->with('success', 'Perubahan Maklumat diluluskan.');
	}

	public function approve($request_id)
	{


		$request = CodeRequest::findOrFail($request_id);

		if (!$request->canProcess() || $request->status != 'pending')
			$this->_access_denied();

		$approval          = new Approval;
		$approval->user_id = auth()->user()->id;
		$approval->save();

		$request->approval_1_id = $approval->id;
		$request->status        = 'approved';
		$request->save();

		$request->updateData();

		// Mail::send('coderequests.emails.approved', ['request' => $request], function ($message) use ($request) {
		// 	$message->to(trim($request->vendor->user->email));
		// 	$message->subject('Perubahan Maklumat Diluluskan');
		// });

		$to			= trim($request->vendor->user->email);
		$subject 	= 'Perubahan Maklumat Diluluskan';
		$send_status = $this->sendMail("html", $to, $subject, "", "coderequests.emails.approved", ['request' => $request]);

		if ($request->type == 'email') {
			// Mail::send('vendors.emails.change_email', ['vendor' => $request->vendor, 'user' => $request->vendor->user], function ($message) use ($request) {
			// 	$message->to(trim($request->data['email']));
			// 	$message->subject('Pengesahan Alamat Emel');
			// });

			$to2			= trim($request->data['email']);
			$subject2		= 'Pengesahan Alamat Emel';
			$send_status 	= $this->sendMail("html", $to2, $subject2, "", "vendors.emails.change_email", ['vendor' => $request->vendor, 'user' => $request->vendor->user]);
		}

		return redirect('requests')->with('success', 'Perubahan Maklumat diluluskan.');
	}

	public function reject_vendor(Request $request, $vendor_id, $request_id)
	{
		if ($vendor_id)
			$vendor = Vendor::findOrFail($vendor_id);

		$request = CodeRequest::findOrFail($request_id);

		if (!$request->canProcess() || $request->status != 'pending')
			$this->_access_denied();

		$request->status            = 'rejected';
		$request->rejection_reason  = $request->reason;
		$request->save();

		// Mail::send('coderequests.emails.rejected', ['request' => $request], function ($message) use ($request) {
		// 	$message->to(trim($request->vendor->registeredBy->email));
		// 	$message->subject('Perubahan Maklumat Ditolak');
		// });

		$to				= trim($request->vendor->registeredBy->email);
		$subject		= 'Perubahan Maklumat Ditolak';
		$send_status 	= $this->sendMail("html", $to, $subject, "", "coderequests.emails.rejected", ['request' => $request]);


		session()->flash('succes', 'Perubahan Maklumat ditolak.');
		return 'true';
	}

	public function reject(Request $request, $request_id)
	{
		$reject = CodeRequest::findOrFail($request_id);

		if (!$reject->canProcess() || $reject->status != 'pending')
			$this->_access_denied();

		$template = null;
		$rejection_reason = null;
		if ($request->template != null) {
			$template = json_encode($request->template);
		}
		if ($request->reason != '') {
			$rejection_reason = $request->reason;
		}
		$reject->status            = 'rejected';
		$reject->rejection_reason  = $rejection_reason;
		$reject->rejection_template_id  = $template;
		$reject->save();

		//mail commented for dev purposed - by zayid 5 nov 22
		Mail::send('coderequests.emails.rejected', ['request' => $reject], function ($message) use ($reject) {
			$message->to(trim($reject->vendor->registeredBy->email));
			$message->subject('Perubahan Maklumat Ditolak');
		});

		session()->flash('succes', 'Perubahan Maklumat ditolak.');
		return 'true';
	}

	public function destroy()
	{
		if ($vendor_id)
			$vendor = Vendor::findOrFail($vendor_id);

		$request = CodeRequest::findOrFail($request_id);

		if (!$request->canDelete() || $request->status != 'pending')
			$this->_access_denied();

		$request->delete();

		if (isset($vendor)) {
			$redirect = redirect('vendor/' . $vendor->id . '/requests');
		} else {
			$redirect = redirect('requests');
		}

		return $redirect->with('success', $this->deleted_message);
	}

	/**
	 * Constructor
	 */

	// public function __construct()
	// {
	// parent::__construct();
	// View::share('controller', 'CodeRequest');
	// }
}
