<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="_token" content="{{csrf_token()}}">
	@yield('meta')
	<title>Resit Pendaftaran Vendor</title>
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css">
	{{ App\Libraries\Asset::tags('css') }}
	<link href="{{ asset('css/application.css') }}" rel="stylesheet">
	<link href="{{ asset('css/form.css') }}" rel="stylesheet">
	<link href="{{ asset('css/receipt.css') }}" rel="stylesheet">
	@yield('styles')
</head>
<body>
	<div class="container">
		<header>
			<div class="col-xs-12" style="text-align:right">Versi 1.0</div>        
			<div class="col-xs-12 logo"><img src="{{ asset('images/jata_selangor.png') }}"></div>
			<div class="col-xs-12 header-title">KERAJAAN NEGERI SELANGOR<br>PEJABAT SETIAUSAHA KERAJAAN NEGERI SELANGOR</div>
			<div class="clearfix"></div>
			<div class="title">Resit Pendaftaran Syarikat</div>
			<div class="col-xs-12 title">RESIT RASMI<br>{{$type}}</div>
			<div class="clearfix"></div>
		</header>

	   <section class="body">
        	<div class="row">
            <div class="col-xs-6 clearfix"></div>
            <div class="col-xs-6">
					<ul class="list-unstyled">
						@if($subscription->transaction->gateway_auth)<li>No Resit : <strong>{{($receipt!='old') ? $receipt : $subscription->transaction->vendor_id . '-' . $subscription->transaction->gateway_reference}}</strong></li>@endif
						<li>Tarikh : <strong>{{\Carbon\Carbon::parse($subscription->transaction->created_at)->format('d / m / Y h:i:s')}}</strong></li>
					</ul>
            </div>
        	</div>
			<div class="row">
				<div class="col-xs-6 address">
					<span>Diterima Daripada: </span>
					<strong>{{$vendor->name}}</strong><br>
					<span>Alamat:</span>
					<strong>{!! nl2br($vendor->address) !!}</strong> <br>
					
					<span>No Akaun/ Rujukan Permohonan: </span><strong>{{$vendor->registration}}</strong>
				</div>
				<div class="col-xs-6">
					<ul class="list-unstyled">
						<li>Kaedah Bayaran: <strong>{{strtoupper($subscription->transaction->method)}}</strong></li>
						<li>Bank: -</li>
						<li>
							No Rujukan Bayaran/ Transaksi : <strong>{{$subscription->transaction->number}}</strong>
						</li>
						@if($subscription->transaction->gateway_auth)
							<li>
								No Pengesahan : <strong>{{$subscription->transaction->gateway_auth}}</strong>
							</li>
						@endif
					</ul>
				</div>
			</div>

        	<table class="table table-bordered table-condensed">
            <thead class="blue">
					<tr>
						<th class="align-right">Bil.</th>
						<th>Keterangan Bayaran/ Tansaksi</th>
						<th>Kod</th>
						<th class="align-right">Jumlah (RM)</th>
					</tr>
            </thead>
            <tbody>
					<tr>
						<td class="align-right">1.</td>
						<td>
							Pendaftaran Syarikat mulai {{\Carbon\Carbon::parse($subscription->start_date)->format('d/m/Y')}} hingga {{\Carbon\Carbon::parse($subscription->end_date)->format('d/m/Y')}}
						</td>
						<td>
							@if($subscription->transaction->type == 'purchase') 73105
							@else 71399
							@endif
						</td>
						<td class="align-right">{{$subscription->transaction->amount}}</td>
					</tr>
					<tr>
						<td class="align-right" colspan="3">Jumlah Keseluruhan</td>
						<td class="align-right">{{$subscription->transaction->amount}}</td>
					</tr>
            </tbody>
        	</table>
        	<div class="row">
            <div class="col-xs-12 total-word">Ringgit Malaysia : {{$subscription->transaction->spellOut()}}</div>
            <div class="clearfix"></div>
        	</div>
        	<div class="row">
            <div class="col-xs-12 address">
					<span>Pusat Terimaan: </span><br>
					@if($subscription->transaction->gateway != null && $subscription->transaction->gateway->agency != null)
						<strong>{{$subscription->transaction->gateway->agency->name}}</strong><br>
						<p>
							{{$subscription->transaction->gateway->agency->address}} 
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
	    	<footer>No Kelulusan Perbendaharaan : </footer>
	    	<footer>Resit ini dihasilkan oleh Sistem Tender Online Selangor</footer>
	</div>
</body>
</html>