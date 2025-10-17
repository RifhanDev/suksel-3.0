<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class VendorCodes implements FromView, WithTitle
{
	protected $vendors;

   public function __construct($vendors) {
		$this->vendors  = $vendors;
   }

   public function view(): View {
        	return view('reports.vendor.code.excel', [
				'vendors'  => $this->vendors,
        	]);
   }

   public function title(): string {
      return 'Senarai';
   }
}
