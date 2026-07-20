<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class AgencyDaily implements FromView, WithTitle
{
	protected $agency, $date, $time, $method, $purchases, $amount;

   public function __construct($agency, $date, $time, $method, $purchases, $amount) {
		$this->agency    = $agency;
		$this->date      = $date;
		$this->time      = $time;
		$this->method    = $method;
		$this->purchases = $purchases;
		$this->amount    = $amount;
   }

   public function view(): View {
     	return view('reports.agency.daily.excel', [
			'agency'    => $this->agency,
			'date'      => $this->date,
			'time'      => $this->time,
			'method'    => $this->method,
			'purchases' => $this->purchases,
			'amount'    => $this->amount,
     	]);
   }

   public function title(): string {
      return $this->date;
   }
}
