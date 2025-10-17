<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!--><html lang="en"><!--<![endif]-->
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="_token" content="{{csrf_token()}}">
	@yield('meta')
	<title>Resit Pembelian Dokumen</title>
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css">
	<link href="{{ asset('css/application.css') }}" rel="stylesheet">
	<link href="{{ asset('css/form.css') }}" rel="stylesheet">
	<link href="{{ asset('css/receipt.css') }}" rel="stylesheet">
</head>
<body>
	<div class="container" style="margin-bottom: 5%;">
		<header>
			<div class="col-xs-12" style="text-align:right">Versi 1.0</div>
			<div class="col-xs-12 logo"><img src={{url("/images/jata_selangor.png")}}></div>
			<div class="col-xs-12 header-title">KERAJAAN NEGERI SELANGOR<br>PEJABAT SETIAUSAHA KERAJAAN NEGERI SELANGOR</div>
			<div class="clearfix"></div>
			<div class="title">RESIT PEMBELIAN DOKUMEN SEBUT HARGA / TENDER</div>
			<div class="col-xs-12 title">RESIT RASMI<br>{{$type}}</div>
			<div class="clearfix"></div>
		</header>

		<section class="body">
			<div class="row">
				<div class="col-xs-6 clearfix"></div>
				<div class="col-xs-6">
					<ul class="list-unstyled">
						@if($purchase->transaction)<li>No Resit : <strong>{{($receipt!='old') ? $receipt : $purchase->transaction->vendor_id . '-' . $purchase->transaction->gateway_reference}}</strong></li>@endif
						<li>Tarikh : <strong>{{\Carbon\Carbon::parse($purchase->transaction->created_at)->format('d / m / Y h:i:s')}}</strong></li>
					</ul>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-6 address">
					<span>Diterima Daripada: </span>
					<strong>{{$purchase->vendor->name}}</strong><br>
					<span>Alamat:</span>
					<strong>{!! nl2br($purchase->vendor->address) !!}</strong> <br>
					
					<span>No Akaun/ Rujukan Permohonan: </span><strong>{{$purchase->vendor->registration}}</strong>
				</div>
				<div class="col-xs-6">
					<ul class="list-unstyled">
					<li>Kaedah Bayaran: <strong>{{strtoupper($purchase->transaction->method)}}</strong></li>
					<li>Bank: -</li>
					<li>No Rujukan Bayaran/ Transaksi : <strong>{{$purchase->transaction->number}}</strong></li>
					@if($purchase->transaction->gateway_auth)<li>No Pengesahan : <strong>{{$purchase->transaction->gateway_auth}}</strong></li>@endif
					</ul>
				</div>
			</div>
		
			<table class="table table-bordered table-condensed">
				<thead class="blue">
					<tr>
						<th class="align-right col-lg-1">No.</th>
						<th>Keterangan Bayaran/ Transaksi</th>
						<th>Kod</th>
						<th class="col-lg-2 align-right">Jumlah (RM)</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="align-right">1</td>
						<td>
							{{$purchase->tender->ref_number}}<br>
							{{$purchase->tender->name}}
						</td>
						<td>@if($purchase->transaction->type == 'purchase') 73105
							@else 71399
							@endif
						</td>
						{{-- <td class="align-right">{{ number_format($purchase->amount, 2, '.', ',') }}</td> --}}
						<td class="align-right">{{ sprintf('%.2f', $purchase->amount) }}</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<th class="align-right" colspan="3">Jumlah Keseluruhan</th>
						<td class="align-right">
							{{-- {{ number_format($purchase->amount, 2, '.', ',') }} --}}
							{{ sprintf('%.2f', $purchase->amount) }}
						</td>
					</tr>
				</tfoot>
			</table>
			<div class="row">
				<!-- <div class="col-xs-12 total-word">Ringgit Malaysia : {{$purchase->spellOut()}}</div> -->
				<div class="col-xs-12 total-word">Ringgit Malaysia : {{$purchase->spellOut()}}</div>
				<div class="clearfix"></div>
			</div>
			<div class="row">
				<div class="col-xs-12 address">
					<span>Pusat Terimaan: </span><br>
					@if($purchase->transaction->gateway != null && $purchase->transaction->gateway->agency != null)
						<strong>{{$purchase->transaction->gateway->agency->name}}</strong><br>
						<p>
						{{$purchase->transaction->gateway->agency->address}} 
						</p>
					@else
						<strong>PEJABAT SETIAUSAHA KERAJAAN NEGERI SELANGOR</strong><br>
						Bangunan Sultan Salahuddin Abdul Aziz Shah 40503 Shah Alam
						Selangor Darul Ehsan <br>
					@endif
				</div>
			</div>
			<div class="row">
				<div class="clearfix"></div>
				<div class="col-xs-12 signature">Ini adalah cetakan komputer dan tidak perlu ditandatangani.</div>
			</div>
		</section>
		<footer>NO. KELULUSAN : </footer>
		<footer>Resit ini dihasilkan oleh Sistem Tender Online Selangor</footer>
		<br>
		<a style="text-decoration: none;" class="hidden-print pull-right" href="javascript:window:print()"><span class="glyphicon glyphicon-print"></span> Cetak</a> <br>
	</div>
</body>
</html>