@extends('layouts.default')
@section('content')
	<div class="alert alert-danger">Transaksi Perbankan FPX sedang dalam proses. Harap bersabar.</div>
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
	    	<td>Online Banking (FPX)</td>
	  	</tr>
	    <tr>
	        <th>Bank</th>
	        <td>{{ App\FpxBank::active()->where('code', Request::get('bank_code'))->first()->display_name }}</td>
	    </tr>
		<tr>
			<th class="col-xs-3">Status</th>
			<td>{{ App\Transaction::$statuses[$transaction->status] }}</td>
		</tr>
	</table>

	<form method="post" id="fpx_connect" action="{{ $transaction->gateway->endpoint_url }}" target="_blank">
	  @foreach($fpx->request_keys as $key => $value)
	  <input type="hidden" name="{{ $key }}" value="{{ $value }}">
	  @endforeach
	  <br />
	  <div class="row">
	      <div class="col-md-12">
	          <div class="checkbox-inline">
	              <label>
			  <input type="checkbox" id="acceptTnc" value="1" />
	                  Saya terima <a href="https://www.mepsfpx.com.my/FPXMain/termsAndConditions.jsp" target="_blank">Terma & Syarat</a>
	              </label>
	          </div>
	      </div>
	  </div>
	  <br />
	  <div class="row">
	      <div class="col-sm-12">
	          <input id="submitBtn" type="submit" value="Sila terima terma dan syarat terlebih dahulu." class="btn bg-blue-selangor" disabled>
	      </div>
	  </div>
	</form>
@endsection

@section('scripts')
	<script type="text/javascript">
	$(document).ready(function(){
		$("#fpx_connect").submit(function(){
			setTimeout(function(){ window.location = '{{ URL::route('txn_status', $transaction->id) }}'; }, 1000);
		});

		$("#acceptTnc").change(function() {
			if(this.checked) {
				$('#submitBtn').prop('disabled', false);
				$('#submitBtn').prop('value', 'Teruskan ke Pembayaran Online Banking (FPX)');
			} else {
				$('#submitBtn').prop('disabled', true);
				$('#submitBtn').prop('value', 'Sila terima terma dan syarat terlebih dahulu.');
			}
		});
	});
	</script>
@endsection
