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
	<title>No Siri Dokumen</title>
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css">
	<link href="{{ asset('css/application.css') }}" rel="stylesheet">
	<link href="{{ asset('css/form.css') }}" rel="stylesheet">
	<link href="{{ asset('css/receipt.css') }}" rel="stylesheet">
</head>
<body>
	<div class="container">
		<header>
			<div class="logo"><img src="{{ asset('images/header.png') }}"></div>
			<div class="title">No Siri Dokumen</div>
			<div class="clearfix"></div>
		</header>
		
		<section class="body">
			<table class="table table-bordered">
				<tr>
					<th class="col-lg-6">No Siri Dokumen Tender / Sebut Harga</th>
					<td>{{ $purchase->ref_number }}</td>
				</tr>
					<tr>
						<th>Nama Syarikat</th>
						<td>{{$purchase->vendor->name}}</td>
					</tr>
				@if($purchase->transaction_id)
					<tr>
						<th>No. Resit</th>
						<td>{{($receipt!='old') ? $receipt : $purchase->vendor_id . '-' . $purchase->transaction->gateway_reference}}</td>
					</tr>
				@endif
				<tr>
					<th>Tarikh Beli</th>
					<td>{{ \Carbon\Carbon::parse($purchase->created_at)->format('d / M / Y') }}</td>
				</tr>
			</table>
		</section>
		<footer>Resit dihasilkan oleh komputer. Tidak perlu ditandatangan.</footer>
		<br><a style="text-decoration: none;" class="hidden-print pull-right" href="javascript:window:print()"><span class="glyphicon glyphicon-print"></span> Cetak</a>
	</div>
</body>
</html>