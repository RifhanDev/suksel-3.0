@extends('layouts.default')
@section('content')
	<h2>Pembaharuan Langganan</h2>
	<br>
	<br>

	<div class="row">
		<div class="col-lg-6 col-lg-offset-3">
			<div class="portlet yellow-crusta box">
				<div class="portlet-title">
					<div class="caption">Pembaharuan Langganan</div>
				</div>
				<div class="portlet-body">
					<h1><center>RM 100</center></h1>
						<center>
						Akses 1 tahun<br>
						mulai {{$start_date->format('d/m/Y')}} hingga {{$end_date->format('d/m/Y')}}
					</center>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
	   <div class="col-lg-6 col-lg-offset-3">
			@if($fpx || $ebpg)        
			{!! Former::open(action('HomeController@storeRenewal'))->class('disabled-submit') !!}
				<center>Silih Pilih Cara Pembayaran</center>
				<br>
				<input type="hidden" name="method">
				<div class="btn-group btn-group-justified">
					@if($ebpg)
						<div class="btn-group">
							<button name="method" id="method-cc" value="ebpg" class="btn btn-lg blue-steel">Kad Kredit</button>
						</div>
					@endif
					@if($fpx)
						<div class="btn-group">
							<a href="#" class="btn btn-lg btn-primary dropdown-toggle" data-toggle="dropdown">Internet Banking (FPX) <span class="caret"></span></a>
							<ul class="dropdown-menu">
							<li><a href="#" class="method-ob" data-value="fpx-1">Perbankan Peribadi</a></li>
							@unless($fpx->private_key == 'b2c')
							<li><a href="#" class="method-ob" data-value="fpx-2">Perbankan Korporat</a></li>
							@endunless
							</ul>
						</div>
					@endif
				</div>
			{!! Former::close() !!}
			@else
			<center>Pembayaran tidak dapat dilakukan pada masa ini kerana masalah teknikal</center>
			@endif
	   </div>
	</div>

	<br>
	<br>
@endsection
@section('scripts')
	<script type="text/javascript">
		$('.method-ob').click(function(){
			method = $(this).data('value');
			$('input[name=method]').val(method);
			$(this).parents('form').submit();
		});
		$("#method-cc").click(function(){
			$('input[name="method"]').val('ebpg');
		})
		$(".selectize").selectize();
	</script>
@endsection
