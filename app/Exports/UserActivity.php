<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class UserActivity implements FromView, WithTitle
{
	protected $data, $tender_activities, $vendor_activities;

   public function __construct($data, $tender_activities, $vendor_activities) {
		$this->data              = $data;
		$this->tender_activities = $tender_activities;
		$this->vendor_activities = $vendor_activities;
   }

   public function view(): View {
        	return view('reports.user.activity.excel', [
				'data'              => $this->data,
				'tender_activities' => $this->tender_activities,
				'vendor_activities' => $this->vendor_activities,
        	]);
   }

   public function title(): string {
      return 'Produktivi Staff';
   }
}
