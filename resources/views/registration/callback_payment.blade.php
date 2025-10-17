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

	@if($transaction->status == 'success')

		<div class="row">
			<div class="col-lg-6 col-lg-offset-3">
				<div class="portlet green-jungle box">
					<div class="portlet-title">
						<div class="caption">Langganan Berjaya</div>
					</div>
					<div class="portlet-body">
						<table class="table table-condensed table-bordered">
							<tr>
								<th class="col-lg-3">No Transaksi</th>
								<td>{{$transaction->number}}</td>
							</tr>
							<tr>
								<th class="col-lg-3">No Resit</th>
								<td>{{$transaction->vendor_id}}-{{$transaction->gateway_reference}}</td>
							</tr>
							<tr>
								<th class="col-lg-3">Kaedah Pembayaran</th>
								<td>{{App\Gateway::$methods[$transaction->method]}}</td>
							</tr>
							<tr>
								<th class="col-lg-3">No Rujukan Pembayaran</th>
								<td>{{$transaction->gateway_reference}}</td>
							</tr>
							<tr>
								<th class="col-lg-3">Jumlah Pembayaran</th>
								<td>RM {{$transaction->amount}}</td>
							</tr>
							<tr>
								<th class="col-lg-3">Tempoh Langganan</th>
								<td>{{\Carbon\Carbon::parse($subscription->start_date)->format('d/m/Y')}} - {{\Carbon\Carbon::parse($subscription->end_date)->format('d/m/Y')}}</td></td>
							</tr>
						</table>
					
						{{link_to_route('vendors.subscriptions.receipt', 'Lihat Resit', [$vendor->id, $subscription->id], ['class' => 'btn green-jungle', 'target' => 'new'])}}
						{{link_to_route('vendor', 'Selesai', [], ['class' => 'btn btn-primary'])}}
					</div>
				</div>
			</div>
		</div>

	@else

		<div class="row">
			<div class="col-lg-6 col-lg-offset-3">
				<div class="portlet red-intense box">
					<div class="portlet-title">
						<div class="caption">Langganan Tidak Berjaya</div>
					</div>
					<div class="portlet-body">
						<table class="table table-condensed">
							<tr>
								<th class="col-lg-3">No Transaksi</th>
								<td>{{$transaction->number}}</td>
							</tr>
							<tr>
								<th class="col-lg-3">No Rujukan Pembayaran</th>
								<td>{{$transaction->gateway_reference}}</td>
							</tr>
							<tr>
								<th class="col-lg-3">Jumlah Pembayaran</th>
								<td>RM {{$transaction->amount}}</td>
							</tr>
							<tr>
								<th class="col-lg-3">Kaedah Pembayaran</th>
								<td>{{App\Gateway::$methods[$transaction->method]}}</td>
							</tr>
							<tr>
								<th>Mesej</th>
								<td>{{$transaction->response_code}}: {{$transaction->response_message}}</td>
							</tr>
						</table>
						{{link_to_route('payment_registration', 'Cuba Semula', [], ['class' => 'btn red-intense'])}}
					</div>
				</div>
			</div>
		</div>

	@endif

	<br>
	<br>
@endsection
