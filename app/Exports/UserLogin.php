<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class UserLogin implements FromView, WithTitle
{
	protected $data, $user;

   public function __construct($data, $user) {
		$this->data = $data;
		$this->user = $user;
   }

   public function view(): View {
        	return view('reports.user.login.excel', [
				'data' => $this->data,
				'user' => $this->user,
        	]);
   }

   public function title(): string {
      return 'Aktiviti Login';
   }
}
