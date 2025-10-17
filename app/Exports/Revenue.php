<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class Revenue implements FromView, WithTitle
{
	protected $data, $fields, $years;

   public function __construct($data,  $fields,  $years) {
      $this->data   = $data;
		$this->fields = $fields;
		$this->years  = $years;
   }

   public function view(): View {
        	return view('reports.revenue.excel', [
				'data'   => $this->data,
				'fields' => $this->fields,
				'years'  => $this->years,
        	]);
   }

   public function title(): string {
      return 'Hasil Transaksi Tahunan';
   }
}
