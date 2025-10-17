@extends('layouts.default')
@section('content')

	<h4 class="tender-title">Laporan Sistem Tender Online: Transaksi Harian Gateway</h4>

	{!! Former::open(action('ReportGatewayDailyController@view'))->target('_blank') !!}
	    	{!! Former::select('gateway_id')
	        ->label('Gateway')
	        ->options($select_gateways)
	        ->placeholder('Pilihan gateway...')
	        ->required() !!}
	    	{!! Former::input('date')
	        ->label('Tarikh')
	        ->required() !!}
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