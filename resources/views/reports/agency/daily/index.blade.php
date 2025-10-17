@extends('layouts.default')
@section('content')

	<h4 class="tender-title">Laporan Sistem Tender Online: Transaksi Harian Agensi</h4>

	{!! Former::open(action('ReportAgencyDailyController@view'))->target('_blank') !!}
	   @if(Auth::user()->can('Report:view:agency_daily'))
	    	{!! Former::select('ou')
				->label('Agensi')
				->options($select_ou)
				->placeholder('Pilihan agensi...')
				->required() !!}
	   @endif
	    	{!! Former::input('date')
				->label('Tarikh')
				->required() !!}
	    	{!! Former::select('method')
				->options(App\Gateway::$methods)
				->placeholder('Pilihan kaedah pembayaran...')
				->label('Kaedah Pembayaran') !!}
	    	{!! Former::input('time')
	        	->label('Waktu Akhir') !!}
	   <div class="form-group">
	      <div class="col-lg-9 col-lg-offset-3">
	        	{!! Former::submit('Hantar')->class('btn bg-blue-selangor') !!}
	      </div>
	   </div>
	{!! Former::close() !!}

@endsection

@section('scripts')

	<script type="text/javascript">
		$(document).ready(function(){
		   $('input[name=date]').datepicker({
		      format: 'yyyy-mm-dd',
		      maxDate: new Date()
		   });
		});
	</script>

@endsection