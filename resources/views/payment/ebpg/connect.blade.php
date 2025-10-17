@extends('layouts.default')
@section('content')
	<div class="alert alert-danger">Transaksi Perbankan Kad Kredit sedang dalam proses. Harap bersabar.</div>
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
			<td>Kad Kredit</td>
		</tr>
		<tr>
			<th class="col-xs-3">Status</th>
			<td>{{ App\Transaction::$statuses[$transaction->status] }}</td>
		</tr>
	</table>

	<form method="post" id="ebpg_connect" action="{{ $transaction->gateway->endpoint_url }}" target="_blank">
		@foreach($ebpg->request_keys as $key => $value)
			<input type ="hidden" name="{{ $key }}" value="{{ $value }}">
		@endforeach
		<input type ="submit" value="Teruskan ke Pembayaran Kad Kredit" class="btn bg-blue-selangor">
	</form>
@endsection

@section('scripts')
	<script type="text/javascript">
		$(document).ready(function(){
			$("#ebpg_connect").submit(function(){
				setTimeout(function(){ window.location = '{{ URL::route('txn_status', $transaction->id) }}'; }, 1000);
			});
		});
	</script>
@endsection
