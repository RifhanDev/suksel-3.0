<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="_token" content="{{csrf_token()}}">
	@yield('meta')
	<title>Resit Pembelian Dokumen</title>
	<link href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css">
	<link href="{{ asset('css/application.css') }}" rel="stylesheet">
	<link href="{{ asset('css/report.css') }}" rel="stylesheet">
	<link href="{{ asset('css/receipt.css') }}" rel="stylesheet">
	@yield('styles')
</head>
<body class="blocks">
	@foreach($purchases as $purchase)
		<div class="receipt-block">
			<header>
				<div class="logo"><img src="{{ asset('images/header.png') }}"></div>
				<div class="title">Resit Pembelian Dokumen</div>
				<div class="clearfix"></div>
			</header>
			
			<section class="body">
				<div class="row">
					<div class="col-xs-6 address">
						<span>Daripada</span><br>
						<strong>SUK Selangor</strong><br>
						Bangunan Sultan Salahuddin Abdul Aziz Shah<br>
						40503 Shah Alam<br>
						Selangor Darul Ehsan
					</div>
				
					<div class="col-xs-5 col-xs-offset-1">
						<ul class="list-unstyled">
							@if($purchase->transaction)
								<li>
									No Resit : 
									<strong>{{$purchase->transaction->vendor_id}}-{{$purchase->transaction->gateway_reference}}</strong>
								</li>
								<li>No Transaksi : <strong>{{$purchase->transaction->number}}</strong></li>
							@endif
							<li>Tarikh : 
								<strong>{{\Carbon\Carbon::parse($purchase->transaction->created_at)->format('d / m / Y')}}</strong>
							</li>
							@if($purchase->transaction && $purchase->transaction->gateway_auth)
								<li>Kod Pengesahan : <strong>{{$purchase->transaction->gateway_auth}}</strong></li>
							@endif
						</ul>
					</div>
				</div>
				
				<div class="row">
					<div class="col-xs-6 address">
					<span>Kepada</span><br>
					<strong>{{$purchase->vendor->name}}</strong><br>
						{{nl2br($purchase->vendor->address)}}
					</div>
				</div>
				
				<table class="table table-bordered table-condensed">
					<thead class="blue">
						<tr>
							<th class="align-right col-lg-1">No.</th>
							<th>Dokumen</th>
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
							<td class="align-right">{{ sprintf('%.2f', $purchase->amount) }}</td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<th class="align-right" colspan="2">Jumlah Keseluruhan</th>
							<td class="align-right">{{ sprintf('%.2f', $purchase->amount) }}</td>
						</tr>
					</tfoot>
				</table>
			</section>
			<footer>Resit dihasilkan oleh komputer. Tidak perlu ditandatangan.</footer>
		</div>
	@endforeach
</body>
</html>