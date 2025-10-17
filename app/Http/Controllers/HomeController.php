<?php

namespace App\Http\Controllers;

use App\Banner;
use App\CodeRequest;
use App\Comment;
use App\Gateway;
use App\Models\Refund;
use App\News;
use App\Tender;
use App\TenderEligible;
use App\TenderInvite;
use App\TenderVendor;
use App\Traits\Helper;
use App\Transaction;
use App\Vendor;
use App\VendorCode;
use Carbon\Carbon;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mail;
use Log;

class HomeController extends Controller
{

	use Helper;
	public function index(Request $request)
	{

		if ($request->ajax()) {
			Log::debug('This is a debug message');
			Log::debug($request->post());

			// Temporarily commented out to fix column issue
			// $base = Tender::orderBy('advertise_start_date', 'desc')->orderBy('submission_datetime', 'desc')->where('submission_datetime', '>=', date('Y-m-d 00:00:00'))->advertised()->forPublic()->published();
			$base = Tender::orderBy('advertise_start_date', 'desc')->orderBy('submission_datetime', 'desc')->where('submission_datetime', '>=', date('Y-m-d 00:00:00'))->advertised()->forPublic();

			switch ($request->type) {
				case 'tenders':
					$tenders = $base->whereType('tender');
					break;
				case 'quotations':
					$tenders = $base->whereType('quotation');
					break;
				default:
					$tenders = $base;
					break;
			}

			$tenders = $tenders->select([
				'id',
				'name',
				'document_start_date',
				'submission_datetime',
				'price',
				'ref_number',
				'organization_unit_id',
				'briefing_required',
				'briefing_address',
				'briefing_datetime'
			]);

			return Datatables::of($tenders)

				->filterColumn('name', function ($query, $keyword) {
					$sql = "CONCAT(name,'-',ref_number)  like ?";
					$query->whereRaw($sql, ["%{$keyword}%"]);
				})
				->editColumn('name', function ($tender) {
					$string   = [];
					$string[] = '<strong><u>' . $tender->tenderer->name . '</u></strong>';
					$string[] = '<small><strong>' . $tender->ref_number . '</strong></small>';
					$string[] = '<a class="table-tender-title" href="' . route('tenders.show', $tender->id) . '">' . $tender->name . '</a>';

					if ($tender->briefing_required) {
						$string[] = '';
						$string[] = '<span class="glyphicon glyphicon-bullhorn"></span> <b><u><small>Kehadiran Taklimat Diwajibkan</small></u></b>';
						$string[] = Carbon::parse($tender->briefing_datetime)->format('j M Y H:i');
						$string[] = nl2br($tender->briefing_address);
					}

					if (count($tender->siteVisits) > 0) {
						$string[] = '';
						$string[] = '<span class="glyphicon glyphicon-road"></span> <b><u><small>Lawatan Tapak</small></u></b>';

						foreach ($tender->siteVisits->sortBy('id') as $visit) {
							$string[] = '';
							$string[] = Carbon::parse($visit->datetime)->format('j M Y H:i');
							$string[] = nl2br($visit->address);

							if ($visit->required) {
								$string[] = '<small><span class="glyphicon glyphicon-ok"></span> Wajib Hadir</small>';
							}
						}
					}

					return implode('<br>', $string);
				})
				->addColumn('codes', function ($tender) {
					$string = '';

					if (count($tender->mof_codes) > 0) {
						$max_count = count($tender->mof_code_groups);
						$string .= '<strong><u>MOF</u></strong><br>';

						foreach ($tender->mof_code_groups_by_code as $order => $data) {
							$code_count = count($data['codes']);
							$i = 1;
							$string .= '<small>';

							foreach ($data['codes'] as $id => $code) {
								$string .= $code;
								if ($i != $code_count)
									$string .= VendorCode::$rule[$data['inner_rule']];
								$i++;
							}

							$string .= '</small>';

							if ($order !=  $max_count)
								$string .= '<br>' . VendorCode::$rule[$data['join_rule']] . '<br>';
						}

						$string .= '<br><br>';
					}

					if (count($tender->cidb_grades) > 0) {
						$string .= '<strong><u>Gred CIDB</u></strong><br><small>';

						foreach ($tender->cidb_grades as $code)
							$string .= $code->code->code . '&nbsp;&nbsp;';

						$string .= '</small><br><br>';
					}

					if (count($tender->cidb_codes) > 0) {
						$max_count = count($tender->cidb_code_groups);
						$string .= '<strong><u>CIDB</u></strong><br>';

						foreach ($tender->cidb_code_groups_by_code as $order => $data) {
							$code_count = count($data['codes']);
							$i = 1;
							$string .= '<small>';

							foreach ($data['codes'] as $id => $code) {
								$string .= $code;
								if ($i != $code_count)
									$string .= VendorCode::$rule[$data['inner_rule']];
								$i++;
							}

							$string .= '</small>';

							if ($order !=  $max_count)
								$string .= '<br>' . VendorCode::$rule[$data['join_rule']] . '<br>';
						}

						$string .= '<br><br>';
					}

					return $string;
				})
				->editColumn('document_start_date', function ($tender) {
					return Carbon::parse($tender->document_start_date)->format('j M Y');
				})
				->editColumn('submission_datetime', function ($tender) {
					return Carbon::parse($tender->submission_datetime)->format('j M Y');
				})
				->editColumn('price', function ($tender) {
					return sprintf('RM %.2f', $tender->price);
				})

				->removeColumn('organization_unit_id')
				->removeColumn('id')
				->removeColumn('briefing_required')
				->removeColumn('briefing_datetime')
				->removeColumn('briefing_address')
				->rawColumns(['name', 'codes', 'document_start_date', 'submission_datetime', 'price'])
				->make();
		}

		switch ($request->type) {
			case 'tenders':
				$path = '/?type=tenders';
				break;
			case 'quotations':
				$path = '/?type=quotations';
				break;
			default:
				$path = '/';
				break;
		}

		$banners = Banner::orderBy('created_at', 'desc')->where('published', 1)->get();
		// $global_news = News::where('show_main', '1')->orderBy('published_at', 'desc')->get();
		$global_news = News::where('published_at', '>=', DB::raw("DATE(DATE_SUB(SYSDATE(), INTERVAL 1 MONTH))"))->orderBy('published_at', 'desc')->get();
		$user = auth()->user();
		return view('home.index', compact('banners', 'path', 'global_news', 'user'));
	}

	public function prices(Request $request)
	{


		if ($request->ajax()) {

			// Temporarily commented out to fix column issue
			// $base = Tender::orderBy('submission_datetime', 'desc')->forPublic()->published()->publishedPrices();
			$base = Tender::orderBy('submission_datetime', 'desc')->forPublic()->publishedPrices();

			switch ($request->type) {
				case 'tenders':
					$tenders = $base->whereType('tender');
					break;
				case 'quotations':
					$tenders = $base->whereType('quotation');
					break;
				default:
					$tenders = $base;
					break;
			}

			$tenders = $tenders->select([
				'id',
				'submission_datetime',
				'organization_unit_id',
				'name',
				'ref_number'
			]);

			return Datatables::of($tenders)

				->filterColumn('name', function ($query, $keyword) {
					$sql = "CONCAT(name,'-',ref_number)  like ?";
					$query->whereRaw($sql, ["%{$keyword}%"]);
				})

				->editColumn('name', function ($tender) {
					$string     = [];
					$string[]   = '<a href="' . action('TendersController@vendors', $tender->id) . '">';
					$string[]   = '<strong>' . $tender->ref_number . '</strong><br>';
					$string[]   = $tender->name . '</a>';
					return implode('', $string);
				})
				->editColumn('organization_unit_id', function ($tender) {
					return $tender->tenderer->name;
				})
				->editColumn('submission_datetime', function ($tender) {
					return Carbon::parse($tender->submission_datetime)->format('j M Y');
				})
				->removeColumn('id')
				->rawColumns(['submission_datetime', 'organization_unit_id', 'name'])
				->make();
		}
		switch ($request->type) {
			case 'tenders':
				$path = '/prices?type=tenders';
				break;
			case 'quotations':
				$path = '/prices?type=quotations';
				break;
			default:
				$path = '/prices';
				break;
		}

		return view('home.prices', compact('path'));
	}

	public function results(Request $request)
	{
		if ($request->ajax()) {
			// Temporarily commented out to fix column issue
			// $base = Tender::orderBy('submission_datetime', 'desc')->forPublic()->published()->publishedPrices()->publishedWinner();
			$base = Tender::orderBy('submission_datetime', 'desc')->forPublic()->publishedPrices()->publishedWinner();
			switch ($request->type) {
				case 'tenders':
					$tenders = $base->whereType('tender');
					break;
				case 'quotations':
					$tenders = $base->whereType('quotation');
					break;
				default:
					$tenders = $base;
					break;
			}

			$tenders = $tenders->select([
				'id',
				'submission_datetime',
				'organization_unit_id',
				'name',
				'ref_number'
			]);

			return Datatables::of($tenders)

				->filterColumn('name', function ($query, $keyword) {
					$sql = "CONCAT(name,'-',ref_number)  like ?";
					$query->whereRaw($sql, ["%{$keyword}%"]);
				})
				->editColumn('name', function ($tender) {
					$string     = [];
					$string[]   = '<a href="' . action('TendersController@vendors', [$tender->id, 'show' => 'winner']) . '">';
					$string[]   = '<strong>' . $tender->ref_number . '</strong><br>';
					$string[]   = $tender->name . '</a>';
					return implode('', $string);
				})
				->editColumn('organization_unit_id', function ($tender) {
					return $tender->tenderer->name;
				})
				->editColumn('submission_datetime', function ($tender) {
					return Carbon::parse($tender->submission_datetime)->format('j M Y');
				})
				->removeColumn('id')
				->rawColumns(['submission_datetime', 'organization_unit_id', 'name'])
				->make();
		}

		switch ($request->type) {
			case 'tenders':
				$path = '/results?type=tenders';
				break;
			case 'quotations':
				$path = '/results?type=quotations';
				break;
			default:
				$path = '/results';
				break;
		}

		return view('home.results', compact('path'));
	}

	public function dashboard()
	{

		$user   = auth()->user();
		if (!$user || !$user->vendor)
			return $this->_access_denied();

		$invites   = TenderInvite::has('tender')->where('vendor_id', auth()->user()->vendor_id)->get();

		$purchases  = TenderVendor::whereNotNull('ref_number')->where('vendor_id', auth()->user()->vendor_id)->orderBy('created_at', 'desc')->get();

		$eligibles_ids = TenderEligible::where('vendor_id', auth()->user()->vendor_id)->groupBy('tender_id')->pluck('tender_id');
		$exception_ids = TenderVendor::where('vendor_id', auth()->user()->vendor_id)->whereException(1)->pluck('tender_id');

		// Temporarily commented out to fix column issue
		// $eligibles = Tender::forPublic()->published()->where('submission_datetime', '>', date('Y-m-d H:i:s'))->has('codes', '=', '0')->get();
		$eligibles = Tender::forPublic()->where('submission_datetime', '>', date('Y-m-d H:i:s'))->has('codes', '=', '0')->get();

		// Temporarily commented out to fix column issue
		// $eligibles = $eligibles->merge(Tender::whereNotNull('approver_id')->whereIn('id', $eligibles_ids)->where('submission_datetime', '>', date('Y-m-d H:i:s'))->get())->sortByDesc('submission_datetime');
		$eligibles = $eligibles->merge(Tender::whereIn('id', $eligibles_ids)->where('submission_datetime', '>', date('Y-m-d H:i:s'))->get())->sortByDesc('submission_datetime');
		$eligibles = $eligibles->merge(Tender::whereIn('id', $exception_ids)->get())->sortByDesc('submission_datetime');
		$eligibles = $eligibles
			->reject(function ($eligible) use ($purchases) {
				return in_array($eligible->id, (array) $purchases->pluck('tender_id'));
			});

		$refunds = Refund::where('vendor_id', auth()->user()->vendor_id)->get();

		foreach ($refunds as $refund) {
			$refund->ref_num = $this->refundNumGenerator($refund->number);
			$refund->status = $refund->refundStatus();
			$refund->receipt = $this->receiptNumGenerator($refund->transaction->number, date('d-m-Y', strtotime($refund->transaction->created_at)));
		}

		return view('home.dashboard', compact('purchases', 'eligibles', 'invites', 'refunds'));
	}

	public function managementDashboard(Request $request)
	{
		$view = $request->view ?? '';

		return view('dashboard.hq.index', compact('view'));
	}

	public function vendor()
	{

		$user   = auth()->user();
		if (!$user || !$user->vendor)
			return $this->_access_denied();

		$vendor = $user->vendor;

		$transactions = $vendor->transactions()->where('type', 'subscription')->where('status', 'success')->get();

		foreach ($transactions as $transaction) {
			$transaction->subscription_id = $transaction->subscription->id;
			$transaction->start_date = Carbon::parse($transaction->subscription->start_date)->format('d/m/Y');
			$transaction->end_date = Carbon::parse($transaction->subscription->end_date)->format('d/m/Y');
		}

		return view('vendors.show', compact('vendor', 'transactions'));
	}

	public function registrations()
	{
		$user = auth()->user();
		return view('home.registrations');
	}

	public function renewal()
	{

		$user   = auth()->user();
		$vendor = $user->vendor;

		if (!$user || !$user->vendor)
			return $this->_access_denied();

		if ($vendor->expired) {
			$start_date = Carbon::now();
			$end_date   = Carbon::now()->addYear();
		} else {
			$start_date = Carbon::parse($vendor->expiry_date);
			$end_date   = Carbon::parse($vendor->expiry_date)->addYear();
		}

		$fpx    = Gateway::whereType('fpx')->whereDefault(1)->whereActive(1)->first();
		$ebpg   = Gateway::whereType('ebpg')->whereDefault(1)->whereActive(1)->first();

		return view('home.renewal', compact('start_date', 'end_date', 'fpx', 'ebpg'));
	}

	public function storeRenewal(Request $request)
	{

		$user   = auth()->user();
		$vendor = $user->vendor;

		if (!in_array($request->method, ['fpx-1', 'fpx-2', 'ebpg']))
			return redirect()->back()->with('error', 'Sila pilih saluran pembayaran yang sah.');

		if ($vendor->expired) {
			$start_date = Carbon::now();
			$end_date   = Carbon::now()->addYear();
		} else {
			$start_date = Carbon::parse($vendor->expiry_date);
			$end_date   = Carbon::parse($vendor->expiry_date)->addYear();
		}

		$cached_data = [
			'renewal' => true,
			'start_date' => $start_date,
			'end_date' => $end_date
		];

		$method = $request->method;
		if (in_array($request->method, ['fpx-1', 'fpx-2'])) {
			$method = 'fpx';
		}

		$gateway    = Gateway::whereType($method)->whereDefault(1)->whereActive(1)->first();

		$transaction = $vendor->transactions()->save(new Transaction([
			'type'                  => 'subscription',
			'renewal'               => true,
			'method'                => $method,
			'status'                => 'pending',
			'user_id'               => $user->id,
			'organization_unit_id'  => isset($gateway) ? $gateway->organization_unit_id : config('app.global_cart_ou'),
			'amount'                => 100,
			'ip'                    => request()->ip(),
			'gateway_id'            => isset($gateway) ? $gateway->id : null,
			'cached_data'           => serialize($cached_data)
		]));

		session()->put('txn_id', $transaction->id);
		session()->put('txn_type', 'renewal');
		if (in_array($request->method, ['fpx-1', 'fpx-2'])) {
			session()->put('fpx_type', $request->method);
		}
		if ($method == 'fpx' && $gateway->version == '7.0') {
			$redirect = redirect('payment/' . $method . "/bank-list");
		} else {
			$redirect = redirect('payment/' . $method . "/connect");
		}
		return $redirect;
	}

	public function callbackRenewal($transaction_id)
	{

		// $transaction    = Transaction::findOrFail(session('txn_id', Input::get('transaction_id')));
		$transaction = Transaction::findOrFail($transaction_id);
		$vendor         = $transaction->vendor;
		$subscription   = $transaction->subscription;

		$year = date('d-m-Y', strtotime($transaction->created_at));
		$receipt = $this->receiptNumGenerator($transaction->number, $year);

		session()->forget('txn_id');
		session()->forget('txn_type');

		if ($transaction->user_id != auth()->user()->id)
			return $this->_access_denied();

		return view('home.callback_renewal', compact('transaction', 'subscription', 'vendor', 'receipt'));
	}

	public function contact(Request $request)
	{

		$comment = new Comment;
		$comment->fill($request->all());
		return view('home.contact', compact('comment'));
	}

	public function doContact(Request $request)
	{

		$data = $request->all();
		Comment::setRules('contact');
		$data['organization_unit_id'] = config('app.global_ou');

		$comment = new Comment;
		$comment->fill($data);

		$redirect = redirect('contact');
		if ($comment->save()) {
			Mail::send(array('text' => 'emails.contact'), ['comment' => $comment], function ($message) use ($comment) {
				$message->from(trim($comment->email));
				$message->to('tenderadmin@selangor.gov.my');
				$message->bcc(trim($comment->email));
				$message->subject('Pesanan dari Sistem Tender Online Selangor');
			});

			$redirect->with('notice', 'Pesanan anda telah dihantar ke agensi berkenaan.');
		} else {
			$redirect->withInput()->withErrors($comment->validationErrors)->with('error', 'Sila pastikan anda mengisi semua medan.');
		}
		return $redirect;
	}

	public function companySearch()
	{
		return view('home.search_company');
	}

	public function doCompanySearch(Request $request)
	{
		$validator = Validator::make(
			$request->only('company_no', 'mof_no', 'cidb_no', 'company_name'),
			[
				'company_no'    => 'required',
				'mof_no'        => 'required_without_all:cidb_no,company_name',
				'cidb_no'       => 'required_without_all:mof_no,company_name',
				'company_name'       => 'required_without_all:cidb_no,mof_no',
			],
			[
				'company_no.required'       => 'No. SSM diperlukan.',
				'mof_no.required_without_all'   => 'No. Rujukan Pendaftaran MOF diperlukan jika tiada No. Pendaftaran CIDB dan Nama Syarikat.',
				'cidb_no.required_without_all'  => 'No. Pendaftaran CIDB diperlukan jika tiada No. Rujukan Pendaftaran MOF dan Nama Syarikat.',
				'company_name.required_without_all'  => 'Nama syarikat diperlukan jika tiada No. Rujukan Pendaftaran MOF dan No. Pendaftaran CIDB.',
			]
		);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		}

		$vendor = Vendor::whereRaw("(UPPER(REGEXP_REPLACE(registration , '[^[:alnum:]]+', ''))) = '" . $this->removeCharacter($request->company_no) . "'");

		if ($request->mof_no) {
			$vendor = $vendor->where('mof_ref_no', $request->mof_no);
		}

		if ($request->cidb_no) {
			$vendor = $vendor->where('cidb_ref_no', $request->cidb_no);
		}

		if ($request->company_name) {
			$vendor = $vendor->where(DB::raw('lower(name)'), 'like', '%' . strtolower($request->company_name) . '%');
		}

		$vendor = $vendor->first();

		if (!$vendor) {
			return redirect()->back()->with('error', 'Harap maaf, tiada syarikat yang berdaftar dengan no pendaftaran <strong>' . $request->company_no . '</strong>!');
		}

		session()->flash('company_search', $vendor->id);

		return view('home.view_company', compact('vendor'));
	}

	public function confirm_email($token)
	{

		$user = User::whereEmailToken($token)->whereNull('email_verify_at')->first();

		if (empty($user)) {
			return redirect('/')->with('error', 'Pautan Pengesahan Emel adalah tidak sah.');
		}

		$user->email_verify_at = Carbon::now();
		$user->save();

		return redirect('/')->with('success', 'Alamat Emel anda telah disahkan.');
	}

	public function changeEmail()
	{
		if (!session()->has('company_search')) {
			return redirect('/')->with('error', 'Sila buat semakan syarikat terlebih dahulu.');
		}

		$vendor = Vendor::find(session('company_search'));

		if (empty($vendor)) {
			return redirect('/')->with('error', 'Sila buat semakan syarikat terlebih dahulu.');
		}

		session()->keep(['company_search']);

		return view('home.change_email', compact('vendor'));
	}

	public function doChangeEmail(Request $request)
	{

		if (!session()->has('company_search')) {
			return redirect('/')->with('error', 'Sila buat semakan syarikat terlebih dahulu.');
		}

		$vendor = Vendor::find(session('company_search'));

		if (empty($vendor)) {
			return redirect('/')->with('error', 'Sila buat semakan syarikat terlebih dahulu.');
		}

		session()->keep(['company_search']);

		$validator = Validator::make(
			$request->only('new_email', 'sijil_ssm', 'sijil_ic'),
			[
				'new_email' => 'required|unique:users,email',
				'sijil_ssm' => 'required',
				'sijil_ic'  => 'required'
			],
			[
				'new_email.required'    => 'Alamat Emel Baru diperlukan',
				'new_email.unique'      => 'Alamat Emel Baru telah digunpakai',
				'sijil_ssm.required'    => 'Salinan Sijil SSM diperlukan',
				'sijil_ic.required'     => 'Salinan Kad Pengenalan Pemilik / Pengarah diperlukan'
			]
		);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		}

		if (!CodeRequest::canCreateFor($vendor->id, 'email'))
			return redirect()->back()->with('error', 'Alamat Emel Syarikat Anda tidak boleh dikemaskini buat masa ini.');

		$data = [
			'email' => $request->new_email,
			'type' => 'email'
		];

		$request = new CodeRequest;
		$request->user()->associate($vendor->registeredBy);
		$request->vendor()->associate($vendor);
		$request->processData($data);
		if (!$request->save())
			return redirect()->back()->with('error', 'Alamat Emel Syarikat Anda tidak boleh dikemaskini buat masa ini.');

		return redirect('/')->with('success', 'Permohonan Kemaskini anda sedang diproses.');
	}

	public function verifyChangeEmail($token)
	{
		$user = User::whereUnconfirmedEmailToken($token)->whereNotNull('unconfirmed_email')->first();

		if (empty($user)) {
			return redirect('/')->with('error', 'Pautan Pengesahan Emel adalah tidak sah.');
		}

		$user->email = $user->username = $user->unconfirmed_email;
		$user->unconfirmed_email = $user->unconfirmed_email_token = null;
		$user->save();

		return redirect('/')->with('success', 'Alamat Emel anda telah disahkan.');
	}

	public function txnStatus($id)
	{
		$refresh = session('txn_status_' . $id, 0);
		if ($refresh == 3) {
			return redirect('/')->with('error', 'Transaksi anda masih belum berjaya.');
		}
		session()->put('txn_status_' . $id, $refresh++);

		$transaction = Transaction::whereUserId(auth()->user()->id)->findOrFail($id);

		if ($transaction->status != 'pending') {
			if ($transaction->type == 'purchase') {
				return redirect('cart/callback/' . $transaction->id);
			}

			if ($transaction->type == 'subscription') {
				return redirect('renewal_callback/' . $transaction->id);
			}
		}

		return view('home.txn_status', compact('transaction'));
	}

	public function versionHistories()
	{
		if (!auth()->user()->can('System:histories')) {
			return $this->_access_denied();
		}

		return view('home.version-histories');
	}

	public function privacy()
	{
		return view('home.privacy_security');
	}

	public function tender_summary_dashboard(Request $request)
	{
		if (!isset($request->year_summary)) {
			$request->year_summary = date('Y');
		}

		$tenders = DB::table('view_tender_dashboard')->select(DB::raw('count(*) as total'))->where('year', $request->year_summary)->groupBy('type');

		$data['tender_count'] = (clone $tenders)->where('type', 'tender')->first();
		$data['quotation_count'] = (clone $tenders)->where('type', 'quotation')->first();

		$tender = $data['tender_count']->total ?? 0;
		$quotation = $data['quotation_count']->total ?? 0;

		$data['total_tender'] = number_format($tender + $quotation);
		$data['tender_count'] = number_format($tender);
		$data['quotation_count'] = number_format($quotation);

		return response()->json($data);
	}

	public function tender_dashboard(Request $request)
	{
		$defaults = ['tender', 'quotation'];
		$data = [];
		$tenders = DB::table('view_tender_dashboard');

		if (!isset($request->tender_view_type)) {
			$request->tender_view_type = 'tender_yearly';
			$request->year_start = date('Y');
		}

		if ($request->tender_view_type == 'tender_yearly') {
			$tenders->select('type', 'month', 'year', DB::raw('count(*) as total'))->where('year', $request->year_start)->groupBy('type', 'month', 'year')->orderBy('type')->orderBy('month');
			foreach ($defaults as $default) {
				$$default = (clone $tenders)->where('type', $default)->pluck('total', 'month');
			}

			for ($i = 1; $i <= 12; $i++) {
				foreach ($defaults as $default) {
					if (!isset($$default[$i])) {
						$$default[$i] = 0;
					}
				}
			}

			foreach ($defaults as $default) {
				if ($default == 'quotation') {
					$new_default = 'Sebutharga';
				} else {
					$new_default = 'Tender';
				}

				$data[$new_default] = array(
					// "data" => $$default->sortKeys()->keys()->all(),
					"data" => ['jan', 'feb', 'mac', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'],
					"value" => $$default->sortKeys()->values()->all(),
				);
			}

			$data['x'] = 'Bulan';
			$data['y'] = 'Bilangan';
			$data['title'] = 'Tahun ' . $request->year_start;
		} else if ($request->tender_view_type == 'tender_monthly') {
			// dd($request->all());
			$date = strtotime($request->monthly_start);
			$month = date('m', $date);
			$year = date('Y', $date);

			$tenders->select('type', 'date', DB::raw('count(*) as total'))->where('year', $year)->where('month', $month)->groupBy('type', 'date')->orderBy('type')->orderBy('date');
			foreach ($defaults as $default) {
				$$default = (clone $tenders)->where('type', $default)->pluck('total', 'date');
			}

			foreach ($defaults as $default) {
				if ($default == 'quotation') {
					$new_default = 'Sebutharga';
				} else {
					$new_default = 'Tender';
				}

				$data[$new_default] = array(
					"data" => $$default->sortKeys()->keys()->all(),
					"value" => $$default->sortKeys()->values()->all(),
				);
			}

			$data['x'] = 'Hari bulan';
			$data['y'] = 'Bilangan';
			$data['title'] = 'Bulan ' . $month . '/' . $year;
		} else if ($request->tender_view_type == 'tender_weekly') {
			// dd($request->all());

			$tenders->select('type', 'week', DB::raw('count(*) as total'))->where('year', $request->year_quarter)->where('quarter', $request->quarter_start)->groupBy('type', 'week')->orderBy('type')->orderBy('week');
			foreach ($defaults as $default) {
				$$default = (clone $tenders)->where('type', $default)->pluck('total', 'week');
			}

			foreach ($defaults as $default) {
				if ($default == 'quotation') {
					$new_default = 'Sebutharga';
				} else {
					$new_default = 'Tender';
				}

				$data[$new_default] = array(
					"data" => $$default->sortKeys()->keys()->all(),
					"value" => $$default->sortKeys()->values()->all(),
				);
			}

			$data['x'] = 'Minggu';
			$data['y'] = 'Bilangan';
			$data['title'] = 'Suku ' . $request->quarter_start . ', ' . $request->year_quarter;
		}

		return response()->json($data);
	}

	public function transaction_value_dashboard(Request $request)
	{
		$defaults = ['purchase', 'subscription'];
		$data = [];
		$transactions = DB::table('view_transaction_dashboard');

		if (!isset($request->transaction_view_type)) {
			$request->transaction_view_type = 'transaction_yearly';
			$request->year_start = date('Y');
		}

		if ($request->transaction_view_type == 'transaction_yearly') {
			$transactions->select('type', 'month', 'year', DB::raw('sum(amount) as total'))->where('year', $request->year_start)->groupBy('type', 'month', 'year')->orderBy('type')->orderBy('month');
			foreach ($defaults as $default) {
				$$default = (clone $transactions)->where('type', $default)->pluck('total', 'month');
			}

			for ($i = 1; $i <= 12; $i++) {
				foreach ($defaults as $default) {
					if (!isset($$default[$i])) {
						$$default[$i] = 0;
					}
				}
			}

			foreach ($defaults as $default) {
				if ($default == 'purchase') {
					$new_default = 'Pembelian Dokumen';
				} else {
					$new_default = 'Langganan';
				}

				$data[$new_default] = array(
					// "data" => $$default->sortKeys()->keys()->all(),
					"data" => ['jan', 'feb', 'mac', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'],
					"value" => $$default->sortKeys()->values()->all(),
				);
			}

			$data['x'] = 'Bulan';
			$data['y'] = 'RM';
			$data['title'] = 'Tahun ' . $request->year_start;
		} else if ($request->transaction_view_type == 'transaction_monthly') {
			// dd($request->all());
			$date = strtotime($request->monthly_start);
			$month = date('m', $date);
			$year = date('Y', $date);

			$transactions->select('type', 'date', DB::raw('sum(amount) as total'))->where('year', $year)->where('month', $month)->groupBy('type', 'date')->orderBy('type')->orderBy('date');
			foreach ($defaults as $default) {
				$$default = (clone $transactions)->where('type', $default)->pluck('total', 'date');
			}

			foreach ($defaults as $default) {
				if ($default == 'purchase') {
					$new_default = 'Pembelian Dokumen';
				} else {
					$new_default = 'Langganan';
				}

				$data[$new_default] = array(
					"data" => $$default->sortKeys()->keys()->all(),
					"value" => $$default->sortKeys()->values()->all(),
				);
			}

			$data['x'] = 'Hari bulan';
			$data['y'] = 'RM';
			$data['title'] = 'Bulan ' . $month . '/' . $year;
		} else if ($request->transaction_view_type == 'transaction_weekly') {
			// dd($request->all());

			$transactions->select('type', 'week', DB::raw('sum(amount) as total'))->where('year', $request->year_quarter)->where('quarter', $request->quarter_start)->groupBy('type', 'week')->orderBy('type')->orderBy('week');
			foreach ($defaults as $default) {
				$$default = (clone $transactions)->where('type', $default)->pluck('total', 'week');
			}

			foreach ($defaults as $default) {
				if ($default == 'purchase') {
					$new_default = 'Pembelian Dokumen';
				} else {
					$new_default = 'Langganan';
				}

				$data[$new_default] = array(
					"data" => $$default->sortKeys()->keys()->all(),
					"value" => $$default->sortKeys()->values()->all(),
				);
			}

			$data['x'] = 'Minggu';
			$data['y'] = 'RM';
			$data['title'] = 'Suku ' . $request->quarter_start . ', ' . $request->year_quarter;
		}

		return response()->json($data);
	}

	public function transaction_dashboard(Request $request)
	{
		$defaults = ['purchase', 'subscription'];
		$data = [];
		$transactions = DB::table('view_transaction_dashboard');

		if (!isset($request->transaction_view_type)) {
			$request->transaction_view_type = 'transaction_yearly';
			$request->year_start = date('Y');
		}

		if ($request->transaction_view_type == 'transaction_yearly') {
			$transactions->select('type', 'month', 'year', DB::raw('count(*) as total'))->where('year', $request->year_start)->groupBy('type', 'month', 'year')->orderBy('type')->orderBy('month');
			foreach ($defaults as $default) {
				$$default = (clone $transactions)->where('type', $default)->pluck('total', 'month');
			}

			for ($i = 1; $i <= 12; $i++) {
				foreach ($defaults as $default) {
					if (!isset($$default[$i])) {
						$$default[$i] = 0;
					}
				}
			}

			foreach ($defaults as $default) {
				if ($default == 'purchase') {
					$new_default = 'Pembelian Dokumen';
				} else {
					$new_default = 'Langganan';
				}

				$data[$new_default] = array(
					// "data" => $$default->sortKeys()->keys()->all(),
					"data" => ['jan', 'feb', 'mac', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'],
					"value" => $$default->sortKeys()->values()->all(),
				);
			}

			$data['x'] = 'Bulan';
			$data['y'] = 'Bilangan';
			$data['title'] = 'Tahun ' . $request->year_start;
		} else if ($request->transaction_view_type == 'transaction_monthly') {
			// dd($request->all());
			$date = strtotime($request->monthly_start);
			$month = date('m', $date);
			$year = date('Y', $date);

			$transactions->select('type', 'date', DB::raw('count(*) as total'))->where('year', $year)->where('month', $month)->groupBy('type', 'date')->orderBy('type')->orderBy('date');
			foreach ($defaults as $default) {
				$$default = (clone $transactions)->where('type', $default)->pluck('total', 'date');
			}

			foreach ($defaults as $default) {
				if ($default == 'purchase') {
					$new_default = 'Pembelian Dokumen';
				} else {
					$new_default = 'Langganan';
				}

				$data[$new_default] = array(
					"data" => $$default->sortKeys()->keys()->all(),
					"value" => $$default->sortKeys()->values()->all(),
				);
			}

			$data['x'] = 'Hari bulan';
			$data['y'] = 'Bilangan';
			$data['title'] = 'Bulan ' . $month . '/' . $year;
		} else if ($request->transaction_view_type == 'transaction_weekly') {
			// dd($request->all());

			$transactions->select('type', 'week', DB::raw('count(*) as total'))->where('year', $request->year_quarter)->where('quarter', $request->quarter_start)->groupBy('type', 'week')->orderBy('type')->orderBy('week');
			foreach ($defaults as $default) {
				$$default = (clone $transactions)->where('type', $default)->pluck('total', 'week');
			}

			foreach ($defaults as $default) {
				if ($default == 'purchase') {
					$new_default = 'Pembelian Dokumen';
				} else {
					$new_default = 'Langganan';
				}

				$data[$new_default] = array(
					"data" => $$default->sortKeys()->keys()->all(),
					"value" => $$default->sortKeys()->values()->all(),
				);
			}

			$data['x'] = 'Minggu';
			$data['y'] = 'Bilangan';
			$data['title'] = 'Suku ' . $request->quarter_start . ', ' . $request->year_quarter;
		}

		return response()->json($data);
	}

	public function transaction_summary_dashboard(Request $request)
	{
		if (!isset($request->year_summary)) {
			$request->year_summary = date('Y');
		}

		$transaction = DB::table('view_transaction_dashboard')->select(DB::raw('coalesce(count(*), 0) as total'))->where('year', $request->year_summary)->groupBy('type');

		$data['subscription_count'] = (clone $transaction)->where('type', 'subscription')->first();
		$data['purchase_count'] = (clone $transaction)->where('type', 'purchase')->first();

		$subscription = $data['subscription_count']->total ?? 0;
		$purchase = $data['purchase_count']->total ?? 0;

		$data['total_transaction'] = number_format($subscription + $purchase);
		$data['subscription_count'] = number_format($subscription);
		$data['purchase_count'] = number_format($purchase);

		return response()->json($data);
	}

	public function transaction_value_summary_dashboard(Request $request)
	{
		if (!isset($request->year_summary)) {
			$request->year_summary = date('Y');
		}

		$transaction = DB::table('view_transaction_dashboard')->select(DB::raw('coalesce(sum(amount), 0) as total'))->where('year', $request->year_summary)->groupBy('type');

		$data['subscription_sum'] = (clone $transaction)->where('type', 'subscription')->first();
		$data['purchase_sum'] = (clone $transaction)->where('type', 'purchase')->first();

		$subscription = $data['subscription_sum']->total ?? 0;
		$purchase = $data['purchase_sum']->total ?? 0;

		$data['total_transaction'] = number_format($subscription + $purchase, 2);
		$data['subscription_sum'] = number_format($subscription, 2);
		$data['purchase_sum'] = number_format($purchase, 2);

		return response()->json($data);
	}

	public function circulars()
	{
		// Return circulars view - you may need to create this view
		return view('home.circulars');
	}

	public function aduanCreate()
	{
		// Return aduan create view - you may need to create this view
		return view('home.aduan-create');
	}

	public function manualShow($manual)
	{
		// Return manual show view - you may need to create this view
		return view('home.manual-show', compact('manual'));
	}

	public function chatWidget()
	{
		// Return chat widget view - you may need to create this view
		return view('home.chat-widget');
	}
}
