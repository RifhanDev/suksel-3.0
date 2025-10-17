@extends('layouts.default')

@section('content')
	<div class="row">
		<div class="col-xs-12 col-sm-6 col-sm-offset-3">
			<h1>Status Transaksi</h1>
			<div style="width: 50px; height: 50px; float: right;"><img src="{{ asset('images/loading.svg') }}"></div>
			<p>Laman ini akan menyemak status transaksi anda setiap 3 minit sebanyak 5 kali. Sekiranya transaksi anda gagal sila berhubung dengan Bahagian Teknologi Maklumat, Pejabat Setiausaha Kerajaan Negeri Selangor</p>
			
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
					<td>{{ App\Gateway::$methods[$transaction->method] }}</td>
				</tr>
				<tr>
					<th class="col-xs-3">Status</th>
					<td>{{ App\Transaction::$statuses[$transaction->status] }}</td>
				</tr>
			</table>
		</div>
	</div>
@endsection

@section('scripts')
	<script type="text/javascript">
		$(document).ready(function(){
		  setTimeout(function(){ window.location.reload(); }, 10000);
		});
	</script>
@endsection
