@extends('layouts.default')
@section('content')

	<h4 class="tender-title">Laporan Sistem Tender Online: Transaksi Agensi Mengikut Tender</h4>

	{!! Former::open(action('ReportAgencyTransactionController@view'))->target('_blank') !!}
	   @if(Auth::user()->can('Report:view:agency_transaction'))
	    	{!! Former::select('ou')
				->label('Agensi')
				->options($select_ou)
				->placeholder('Pilihan agensi...')
				->required() !!}
	   @endif
	    	{!! Former::select('year')
				->label('Tahun')
				->options($select_year)
				->placeholder('Pilihan tahun...')
				->required() !!}
	    	{!! Former::select('month')
				->label('Bulan')
				->options($select_month)
				->placeholder('Pilihan bulan...')
				->required() !!}
	    	<div class="form-group">
	      	<div class="col-lg-9 col-lg-offset-3">
	        		{!! Former::submit('Hantar')->class('btn bg-blue-selangor') !!}
	      	</div>
	    	</div>
	{!! Former::close() !!}

@endsection
