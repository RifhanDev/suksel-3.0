@extends('layouts.default')
@section('content')

	<h2>Maklumat Transaksi: {{$transaction->number}}</h2>
	<hr>
	
	<table class="table table-bordered">
		<tr>
			<th class="col-lg-3">Nama Syarikat</th>
			<td>{{$transaction->vendor->name}}</td>
		</tr>
		
		<tr>
			<th>Agensi Pembayaran</th>
			<td>{{$transaction->agency->name}}</td>
		</tr>
		
		<tr>
			<th>Tarikh Transaksi</th>
			<td>{{ Carbon\Carbon::parse($transaction->created_at)->format('j M Y H:i:s')}}</td>
		</tr>
		
		<tr>
			<th>No Transaksi</th>
			<td>{{$transaction->number}}</td>
		</tr>
		
		@if($transaction->gateway_reference)
			<tr>
				<th>No Resit</th>
				<td>{{($receipt!='old') ? $receipt : $transaction->vendor_id . '-' . $transaction->gateway_reference}}</td>
			</tr>
		@endif
		
		<tr>
			<th>Jenis Pembayaran</th>
			<td>{{ App\Transaction::$types[$transaction->type]}}</td>
		</tr>
		
		<tr>
			<th>Jumlah</th>
			<td>RM {{number_format($transaction->amount,2,'.',',')}}</td>
		</tr>
		
		<tr>
			<th>Status</th>
			<td>{{ App\Transaction::$statuses[$transaction->status]}}</td>
		</tr>
		
		@if($transaction->ebpg_signature)
			<tr>
				<th>eBPG Signature</th>
				<td>{!! wordwrap($transaction->ebpg_signature, 40, "<br>\n", true) !!} @if($transaction->valid_ebpg_signature)<span class="glyphicon glyphicon-ok"></span>@endif</td>
			</tr>
		@endif
		
		@if($transaction->ebpg_signature_2)
			<tr>
				<th>eBPG Signature 2</th>
				<td>{!! wordwrap($transaction->ebpg_signature_2, 40, "<br>\n", true) !!} 
					@if($transaction->valid_ebpg_signature_2)<span class="glyphicon glyphicon-ok"></span>@endif
				</td>
			</tr>
		@endif
		
		<tr>
			<th>No Rujukan Pembayaran</th>
			<td>@if($transaction->gateway_reference){{$transaction->gateway_reference}}@else<span class="glyphicon glyphicon-remove"></span>@endif	</td>
		</tr>
		
		<tr>
			<th>No Kebenaran Pembayaran</th>
			<td>@if($transaction->gateway_auth){{$transaction->gateway_auth}}@else<span class="glyphicon glyphicon-remove"></span>@endif</td>
		</tr>
		<tr>
			<th>Mesej Sistem</th>
			<td>
				@if($transaction->response_message)
					{!! $transaction->response_message !!}
				@else
					{!! boolean_icon(false) !!}
				@endif
			</td>
		</tr>
	</table>
	
	@if($transaction->type == 'subscription')
	
		<h4>Maklumat Langganan</h4>
		<table class="table table-bordered">
			@if($transaction->subscription)
				<tr>
					<th class="col-lg-3">Tempoh Langganan</th>
					<td>{{\Carbon\Carbon::parse($transaction->subscription->start_date)->format('d/m/Y')}} - {{\Carbon\Carbon::parse($transaction->subscription->end_date)->format('d/m/Y')}}</td>
				</tr>
				<tr>
					<th>Resit Pembayaran</th>
					<td>{{link_to_route('vendors.subscriptions.receipt', 'Resit', [$transaction->vendor_id, $transaction->subscription->id], ['target' => 'new', 'class' => 'btn btn-xs btn-warning'])}}</td>
				</tr>
			@elseif($transaction->cached_subscription)
				<tr>
					<th class="col-lg-3">Tempoh Langganan</th>
					<td>{{\Carbon\Carbon::parse($transaction->cached_subscription->start_date)->format('d/m/Y')}} - {{\Carbon\Carbon::parse($transaction->cached_subscription->end_date)->format('d/m/Y')}}</td>
				</tr>
				@if($transaction->status == 'success')
					<tr>
						<th>Resit Pembayaran</th>
						<td>{{link_to_route('transactions.temp_receipt', 'Resit', $transaction->id, ['target' => 'new', 'class' => 'btn btn-xs btn-warning'])}}</td>
					</tr>
				@endif
				@else
					<tr>
						<th class="col-lg-3">Tempoh Langganan</th>
						<td>Tiada Maklumat Langganan</td>
					</tr>
			@endif       
		</table>
		<br>
	
	@endif
	
	@if($transaction->type == 'purchase')
	
		<h4>Maklumat Dokumen</h4>
		
		<table class="table table-bordered table-condensed">
			<thead class="bg-blue-selangor">
				<tr>
					<th>Tender / Sebut Harga</th>
					<th class="col-lg-2">&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				@forelse($transaction->purchases as $purchase)
					<tr>
						<td>
							<strong><u>{{ $purchase->tender->tenderer->name }}</u></strong><br>
							<a href="{{ asset('tenders/'.$purchase->tender_id) }}">
							<small>{{$purchase->tender->ref_number}}</small><br>
								{{$purchase->tender->name}}
							</a>
						</td>
						<td>
							RM {{ sprintf('%.2f', $purchase->amount) }}<br>
							<a href="{{ asset('tenders/'.$purchase->tender_id.'/receipt/'.$purchase->id) }}" target="_blank"><i class="icon-printer"> Resit</i></a><br>
							<a href="{{ asset('tenders/'.$purchase->tender_id.'/document/'.$purchase->id) }}" target="_blank"><i class="icon-doc"> No. Siri Dokumen</i></a>
						</td>
					</tr>
				@empty
					@foreach($transaction->cached_purchases as $tender)
						<tr>
							<td>
								<strong><u>{{ $tender->tenderer->name }}</u></strong><br>
								<a href="{{ route('tenders.show', $tender->id) }}">
								<small>{{$tender->ref_number}}</small><br>
									{{$tender->name}}
								</a>
							</td>
							<td>
								RM {{ sprintf('%.2f', $tender->price) }}
								@if($transaction->status == 'success')
								<br><a href="{{ route('transactions.temp_receipt', [$transaction->id, 'tender_id' => $tender->id]) }}" target="_blank"><i class="icon-printer"> Resit</i></a><br>
								@endif
							</td>
						</tr>
					@endforeach
				@endforelse
			</tbody>
		</table>
		<br>
	@endif
	
	<div class="well">
		@if($transaction->canUpdate())
			<a href="{{ asset('transactions/'.$transaction->id.'/edit') }}" class="btn btn-primary">Kemaskini</a>
		@endif
	
		@if($transaction->gateway && $transaction->gateway->type == 'ebpg')
			<form method="post" id="transaction_query" class="form-inline" action="{{ $transaction->gateway->daemon_url}}">
				<input type="hidden" name="MERCHANT_ACC_NO" value="{{ $transaction->gateway->merchant_code }}">
				<input type="hidden" name="MERCHANT_TRANID" value="{{$transaction->gateway->transaction_prefix }}{{ $transaction->number }}">
				<input type="hidden" name="AMOUNT" value="{{ $transaction->amount }}">
				<input type="hidden" name="TRANSACTION_TYPE" value="1">
				<input type="hidden" name="TXN_SIGNATURE" value="<?php echo hash('sha512', "{$transaction->gateway->private_key}{$transaction->gateway->merchant_code}{$transaction->gateway->transaction_prefix}{$transaction->number}{$transaction->amount}"); ?>">
				<input type="hidden" name="RESPONSE_TYPE" value="PLAIN">
				<input type="submit" value="Data eBPG" class="btn btn-warning">
			</form>
		
			@if($transaction->canUpdate())
				<form method="post" id="ebpg_requery" class="form-inline" action="{{ $transaction->gateway->daemon_url}}">
					<input type="hidden" name="MERCHANT_ACC_NO" value="{{ $transaction->gateway->merchant_code }}">
					<input type="hidden" name="MERCHANT_TRANID" value="{{$transaction->gateway->transaction_prefix }}{{ $transaction->number }}">
					<input type="hidden" name="AMOUNT" value="{{ $transaction->amount }}">
					<input type="hidden" name="TRANSACTION_TYPE" value="1">
					<input type="hidden" name="TXN_SIGNATURE" value="<?php echo hash('sha512', "{$transaction->gateway->private_key}{$transaction->gateway->merchant_code}{$transaction->gateway->transaction_prefix}{$transaction->number}{$transaction->amount}"); ?>">
					<input type="hidden" name="RESPONSE_TYPE" value="HTTP">
					<input type="hidden" name="RETURN_URL" value="{{ route('transactions.ebpg_requery', $transaction->id) }}">
					<input type="submit" value="Kemaskini Data eBPG" class="btn btn-danger">
				</form>
			@endif
		@endif
	
	@if($transaction->gateway && $transaction->gateway->type == 'fpx')
		<a href="{{ route('transactions.fpx_query', $transaction->id) }}" class="btn btn-warning txn-data-link">Data FPX</a>
	
		@if($transaction->canUpdate())
			<a href="{{ route('transactions.fpx_requery', $transaction->id) }}" class="btn btn-danger">Kemaskini Data FPX</a>
		@endif
	@endif
	
	
	<a href="{{ asset('transactions') }}" class="btn btn-default pull-right">Senarai Transaksi</a>
		<div class="clearfix"></div>
	</div>

@endsection

@section('scripts')

	<script type="text/javascript">
		$(document).ready(function(){
			$(document).on('submit','form#transaction_query',function(){
				window.open('about:blank','Popup_Window','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=500,height=500,left = 50,top = 50');
				this.target = 'Popup_Window';
			});
			
			$('.txn-data-link').click(function(evt){
				var w = window.open(this.href, 'Popup_Window','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=500,height=500,left = 50,top = 50');    
				evt.preventDefault();
				return false;
			});
		});
	</script>

@endsection