<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class VendorStatus implements FromView, WithTitle
{
	protected $vendors, $status, $date_label;

   public function __construct($vendors, $status, $date_label) {
		$this->vendors    = $vendors;
		$this->status     = $status;
		$this->date_label = $date_label;
   }

   public function view(): View {
        	return view('reports.vendor.status.excel', [
				'vendors'    => $this->vendors,
				'status'     => $this->status,
				'date_label' => $this->date_label,
        	]);
   }

   public function title(): string {
      return $this->status;
   }
}
