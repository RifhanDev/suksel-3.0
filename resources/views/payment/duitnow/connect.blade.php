@extends('layouts.v3.master')
@section('content')
	<div class="alert alert-info">
		<strong>Pembayaran DuitNow</strong><br>
		Transaksi pembayaran DuitNow sedang dalam proses. Sila ikuti arahan di bawah.
	</div>
	<table class="table table-bordered">
		<tr>
			<th class="col-xs-3">No Transaksi</th>
			<td>{{ $transaction->number }}</td>
		</tr>
		<tr>
			<th class="col-xs-3">Jumlah</th>
			<td>MYR {{ sprintf('%.2f', $transaction->amount) }}</td>
		</tr>
		<tr>
			<th>Kaedah Pembayaran</th>
			<td>DuitNow</td>
		</tr>
		<tr>
			<th class="col-xs-3">Status</th>
			<td>{{ App\Transaction::$statuses[$transaction->status] }}</td>
		</tr>
	</table>

	<form method="post" id="duitnow_connect" action="{{ $transaction->gateway->endpoint_url }}" target="_blank">
		@foreach ($duitnow->request_keys as $key => $value)
			<input type="hidden" name="{{ $key }}" value="{{ $value }}">
		@endforeach
		<br />
		<div class="row">
			<div class="col-md-12">
				<div class="alert alert-warning">
					<p><strong>Nota Penting:</strong></p>
					<ul>
						<li>Anda akan diarahkan ke halaman pembayaran DuitNow</li>
						<li>Sila lengkapkan pembayaran menggunakan aplikasi DuitNow atau bank anda</li>
						<li>Jangan tutup halaman ini sehingga pembayaran selesai</li>
					</ul>
				</div>
			</div>
		</div>
		<br />
		<div class="row">
			<div class="col-sm-12">
				<input id="submitBtn" type="submit" value="Teruskan ke Pembayaran DuitNow" class="btn bg-blue-selangor btn-lg">
			</div>
		</div>
	</form>
@endsection

@section('scripts')
	<script type="text/javascript">
		$(document).ready(function() {
			$("#duitnow_connect").submit(function() {
				// Optional: Show loading message
				$("#submitBtn").prop('disabled', true).val('Sedang memproses...');

				// Optional: Redirect to status page after a delay
				setTimeout(function() {
					// window.location = '{{ URL::route('txn_status', $transaction->id) }}';
				}, 2000);
			});
		});
	</script>
@endsection
