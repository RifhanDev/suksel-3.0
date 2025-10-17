<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class UserAgency implements FromView, WithTitle
{
	protected $users, $agency;

   public function __construct($users, $agency) {
		$this->users  = $users;
		$this->agency = $agency;
   }

   public function view(): View {
        	return view('reports.user.agency.excel', [
				'users'  => $this->users,
				'agency' => $this->agency,
        	]);
   }

   public function title(): string {
      return $this->agency->name;
   }
}
