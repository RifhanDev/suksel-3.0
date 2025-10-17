<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use App\Vendor;

class VendorDistrict implements FromView, WithTitle
{
	protected $district, $vendors;

   public function __construct($district, $vendors) {
		$this->district = $district;
		$this->vendors  = $vendors;
   }

   public function view(): View {
        	return view('reports.vendor.district.excel', [
				'district' => $this->district,
				'vendors'  => $this->vendors,
        	]);
   }

   public function title(): string {
      return $this->district == 'all' ? 'Semua' : $this->district;
   }
}
