<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Excel;
use App\Exports\GatewayDaily;
use Illuminate\Support\Facades\Validator;
use App\Transaction;
use App\Gateway;

class ReportGatewayDailyController extends Controller
{
	public function index() {
     	$gateways = Gateway::with('agency');

		if(auth()->user()->can('Report:view:gateway_daily:organization_unit_id'))
			$gateways   = $gateways->where('organization_unit_id', auth()->user()->organization_unit_id);
	
		$gateways = $gateways->where('type', '!=', 'lock')->get();
		
		$select_gateways = [
			'Gateway'   => [],
			'No. Akaun' => []
		];
		
		foreach($gateways as $gateway) {
			$select_gateways['Gateway'][$gateway->id] = sprintf(
				'%s: %s (%s)',
				Gateway::$methods[$gateway->type],
				$gateway->agency->name,
				$gateway->merchant_code );
		}
			
			$mids   = Gateway::groupBy('merchant_code')->where('merchant_code', '!=', '');
			
			if(auth()->user()->can('Report:view:gateway_daily:organization_unit_id'))
				$mids   = $mids->where('organization_unit_id', auth()->user()->organization_unit_id);
		
			$mids   = $mids->get();
		
		foreach($mids as $gateway) {
			$select_gateways['No. Akaun']['all-' . $gateway->merchant_code] = sprintf(
				'%s: %s (%s)',
				Gateway::$methods[$gateway->type],
				$gateway->agency->name,
				$gateway->merchant_code
			);
		}
		
		return view('reports.gateway.daily.index', compact('select_gateways'));
   }

   public function view(Request $request) {

		$gateway_id = $request->input('gateway_id', null);
		$date       = $request->input('date', null);
		$time       = $request->input('time', null);
		$type       = $request->input('type', null);
		
		$validator = Validator::make([
				'gateway_id' => $gateway_id,
				'date'       => $date
			],[
				'gateway_id' => 'required',
				'date'       => 'required',
			]);
		$validator->setAttributeNames([
			'gateway' => 'Gateway',
			'date'    => 'Tarikh'
		]);
		
		if($validator->fails()) {
			$title = 'Transaksi Harian Gateway';
			$error = 'Sila pastikan pilihan medan adalah betul.';
			return view('reports.error', compact('title', 'error'));
		}
		
		if(starts_with($gateway_id, 'all-')) {
			$mid     = explode('-', $gateway_id)[1];
			$gateway = Gateway::whereMerchantCode($mid)->first();
		}
		else {
			$gateway = Gateway::find($gateway_id);
		}

		list($transactions, $amount) = $this->query($gateway_id, $date, $time, $type);
		
		return view('reports.gateway.daily.view', compact('gateway', 'date', 'time', 'transactions', 'amount', 'type'));
	}

	protected function excel(Request $request) {
			$gateway_id = $request->input('gateway_id', null);
			$date       = $request->input('date', null);
			$time       = $request->input('time', null);

        	if(starts_with($gateway_id, 'all-')) {
            $mid        = explode('-', $gateway_id)[1];
            $gateway    = Gateway::whereMerchantCode($mid)->first();
        	}
        	else {
            $gateway = Gateway::find($gateway_id);
        	}

        	list($transactions, $amount)    = $this->query($gateway_id, $date, $time);

        	return Excel::download(new GatewayDaily($gateway, $date, $time, $transactions, $amount), 'Transaksi Harian Gateway.xlsx');
   }

   protected function query($gateway_id, $date, $time=null) {
     	if($time) {
			$date  = Carbon::parse($date . ' ' . $time);
			$start = $date->copy()->subDay();
			$end   = $date->copy();
     	} else {
			$date  = Carbon::parse($date);
			$start = $date->copy()->startOfDay();
			$end   = $date->copy()->endOfDay();
     	}

     $base = Transaction::where('status', 'success')
							->with(['vendor', 'agency', 'subscription', 'purchases', 'purchases.tender', 'purchases.tender.tenderer'])
							->whereBetween('created_at', [$start, $end])
							->orderBy('created_at', 'asc'); 

     	if(starts_with($gateway_id, 'all-')) {
			$mid  = explode('-', $gateway_id)[1];
			$base = $base->whereHas('gateway', function($q) use($mid) {
				$q->whereMerchantCode($mid);
         });
     	}
     	else {
         $base   = $base->whereGatewayId($gateway_id);
     	}

     	$transactions   = $base->get();
     	$amount         = sprintf('%.2f', $base->sum('amount'));

     	return [$transactions, $amount];
   }

    // public function __construct()
    // {
    //     parent::__construct();
    //     View::share('controller', 'ReportGatewayDaily');
    // }
}
