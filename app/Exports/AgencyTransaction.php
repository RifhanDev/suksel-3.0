<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class AgencyTransaction implements FromView, WithTitle
{
	protected $tenders;

   public function __construct($tenders) {
      $this->tenders = $tenders;
   }

   public function view(): View {
        	return view('reports.agency.transaction.excel', [
            'tenders' => $this->tenders
        	]);
   }

   public function title(): string {
      return 'TMT';
   }
}
