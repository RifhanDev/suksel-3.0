<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class GatewayDaily implements FromView, WithTitle
{
	protected $gateway, $date, $time, $transactions, $amount;

   public function __construct($gateway, $date, $time, $transactions, $amount) {
		$this->gateway      = $gateway;
		$this->date         = $date;
		$this->time         = $time;
		$this->transactions = $transactions;
		$this->amount       = $amount;
   }

   public function view(): View {
        	return view('reports.gateway.daily.excel', [
				'gateway'      => $this->gateway,
				'date'         => $this->date,
				'time'         => $this->time,
				'transactions' => $this->transactions,
				'amount'       => $this->amount,
        	]);
   }

   public function title(): string {
      return $this->date;
   }
}
