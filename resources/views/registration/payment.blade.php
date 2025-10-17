@extends('layouts.default')
@section('content')
	<h2>Pendaftaran Vendor</h2>
	<ul class="nav nav-tabs nav-justified">
		<li class="disabled">
			<a href="#"><span class="badge">1</span> Pengesahan Alamat Emel</a>
		</li>
		<li class="disabled">
			<a href="#"><span class="badge">2</span> Lengkapkan Maklumat Syarikat</a>
		</li>
		<li class="active">
			<a href="#"><span class="badge">3</span> Pembayaran Pendaftaran</a>
		</li>
	</ul>

	<br>
	<br>

	<div class="row">
		<div class="col-lg-6 col-lg-offset-3">
			<div class="portlet yellow-crusta box">
			<div class="portlet-title">
				<div class="caption">Langganan</div>
			</div>
			<div class="portlet-body">
				<h1><center>RM 100</center></h1>
				<center>
					Akses 1 tahun<br>
					mulai {{\Carbon\Carbon::now()->format('d/m/Y')}} hingga {{\Carbon\Carbon::now()->addYear()->format('d/m/Y')}}
				</center>
			</div>
			</div>
		</div>
	</div>

	<div class="row">
	    <div class="col-lg-6 col-lg-offset-3">
	        @if($fpx || $ebpg)
	        <center>Silih Pilih Cara Pembayaran</center>
	        <br>

	        {!! Former::open(action('RegistrationController@storePayment'))->class('disabled-submit') !!}
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
	                    <li><a href="#" class="method-ob" data-value="fpx-2">Perbankan Korporat</a></li>
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
	</script>
@endsection
