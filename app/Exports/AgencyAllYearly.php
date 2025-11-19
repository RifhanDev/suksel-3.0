<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class AgencyAllYearly implements FromView, WithTitle
{
	protected $data, $years;

   public function __construct($data, $years) {
		$this->data  = $data;
		$this->years = $years;
   }

   public function view(): View {
     	return view('reports.agency.all.excel_yearly', [
			'data'  => $this->data,
			'years' => $this->years,
     	]);
   }

   public function title(): string {
      return 'Tahunan';
   }
}
