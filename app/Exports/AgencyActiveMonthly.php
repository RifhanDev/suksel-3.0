<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class AgencyActiveMonthly implements FromView, WithTitle
{
	protected $data, $year;

   public function __construct($data, $year) {
		$this->data = $data;
		$this->year = $year;
   }

   public function view(): View {
     	return view('reports.agency.active.excel_monthly', [
			'data' => $this->data,
			'year' => $this->year,
     	]);
   }

   public function title(): string {
      return $this->year;
   }
}
