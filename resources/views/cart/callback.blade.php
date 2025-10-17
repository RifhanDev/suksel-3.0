@extends('layouts.default')

@section('content')
	<h4 class="pull-left">Senarai Tempahan</h4>
	<a href="javascript:window.print()" class="pull-right print hidden-print"><i class="fa fa-print"></i> Cetak</a>
	<div class="clearfix"></div>

	@if($transaction->status == 'success')
		<div class="alert alert-success">Pembelian Dokumen Anda Berjaya!</div>
	@endif
	@if($transaction->status == 'failed')
		<div class="alert alert-danger">
		    <strong>Pembayaran Anda Gagal</strong><br>
		    ({{ $transaction->response_code }}) {{ $transaction->response_message }}
		</div>
	@endif
	@if($transaction->status == 'pending_authorization')
		<div class="alert alert-info">Pembayaran Anda Dalam Proses Pengesahan</div>
	@endif

	<table class="DT table table-hover table-compact">
		<thead class="bg-blue-selangor">
			<tr>
				<th class="col-lg-2">Petender</th>
				<th class="col-lg-4">No / Tajuk</th>
				<th>Tarikh Jual</th>
				<th>Tarikh Serahan</th>
				<th>Harga Dokumen</th>
				@if($transaction->status == 'success')<th>&nbsp;</th>@endif
			</tr>
		</thead>
		<tbody>
			@forelse($tenders as $tender)
				<tr>
					<td><a href="{{ asset('agencies/'.$tender->organization_unit_id) }}">{{ $tender->tenderer->name }}</a></td>
					<td>
						<a href="{{ asset('tenders/'.$tender->id)}}">
						<strong>{{$tender->ref_number}}</strong>
						<br>{{$tender->name}}
						</a>
					</td>
					<td>{{\Carbon\Carbon::parse($tender->document_start_date)->format('j M Y')}}</td>
					<td>{{\Carbon\Carbon::parse($tender->submission_datetime)->format('j M Y')}}</td>
					<td>RM {{ sprintf('%.2f', $tender->price) }}</td>
					@if($transaction->status == 'success')
						<td>
						<a href="{{ asset('tenders/'.$tender->id.'/receipt/'.$tender->participants()->whereVendorId(Auth::user()->vendor_id)->first()->id) }}" target="_blank"><i class="icon-printer"> Resit</i></a><br>
						<a href="{{ asset('tenders/'.$tender->id.'/document/'.$tender->participants()->whereVendorId(Auth::user()->vendor_id)->first()->id) }}" target="_blank"><i class="icon-doc"> No. Siri Dokumen</i></a>
						</td>
					@endif
				</tr>
			@empty
				<tr>
					<td colspan="5"><center>Tiada tender dalam senarai tempahan</center></td>
				</tr>
			@endforelse
		</tbody>
		<tfoot>
			<tr>
				<td colspan="4" class="align-right">Jumlah Tender</td>
				<td @if($transaction->status == 'success') colspan="2" @endif >{{ count($tenders) }}</td>
			</tr>
			<tr>
				<td colspan="4" class="align-right"><strong>Jumlah Bayaran</strong></td>
				<td @if($transaction->status == 'success') colspan="2" @endif >RM {{ sprintf('%.2f', $amount) }}</td>
			</tr>
			<tr>
				<td colspan="4" class="align-right"><strong>Tarikh &amp; Masa Bayaran</strong></td>
				<!-- <td @if($transaction->status == 'success') colspan="2" @endif >{{ $transaction->created_at->format('d/m/Y H:i:s') }}</td> -->
				<td @if($transaction->status == 'success') colspan="2" @endif >{{ $transaction->sellerTxnTime }}</td>
			</tr>
			<tr>
			<tr>
				<td colspan="4" class="align-right"><strong>No Transaksi</strong></td>
				<td @if($transaction->status == 'success') colspan="2" @endif >{{ $transaction->number }}</td>
			</tr>
			@if($transaction->status == 'success')
			<tr>
				<td colspan="4" class="align-right"><strong>No Resit</strong></td>
				<td @if($transaction->status == 'success') colspan="2" @endif >{{$transaction->vendor_id}}-{{$transaction->gateway_reference}}</td>
			</tr>
			@endif
			<tr>
				<td colspan="4" class="align-right"><strong>Kaedah Pembayaran</strong></td>
				<td @if($transaction->status == 'success') colspan="2" @endif >{{App\Gateway::$methods[$transaction->method]}}</td>
			</tr>
			<tr>
				<td colspan="4" class="align-right"><strong>No Rujukan Pembayaran</strong></td>
				<td @if($transaction->status == 'success') colspan="2" @endif >{{$transaction->gateway_reference}}</td>
			</tr>
			@if($transaction->method == 'fpx' && $transaction->bank_name)
			<tr>
				<td colspan="4" class="align-right"><strong>Bank Pembayaran</strong></td>
				<td @if($transaction->status == 'success') colspan="2" @endif >{{$transaction->bank_name}}</td>
			</tr>
			@endif
		</tfoot>
	</table>
	<br>
	<div class="well">
		@if($transaction->status == 'failed')
			{!! Former::open(route('cart.process'))->class('form-inline') !!}
				<input type="hidden" name="method">
		
				<p id="payment-kinds" class="pull-left">
					<span>Pembelian Dokumen Tender / Sebut Harga boleh dilakukan menggunakan</span><br><br>
					@if($ebpg)
						<i class="icon icon-visa"></i>
						<i class="icon icon-mastercard"></i>
					@endif
					@if($fpx)
						<i class="temp-icon tem-icon-fpx"></i>
					@endif
				</p>
			
				<div id="payment-options" class="pull-right">
					<div class="text">
						Pembayaran Semula?
					</div>
					<div class="btn-toolbar">
						@if($amount > 0.00)
							@if($fpx)
								<div class="btn-group">
								<a href="#" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Internet Banking (FPX) <span class="caret"></span></a>
								<ul class="dropdown-menu">
								<li><a href="#" class="method-ob" data-value="fpx-1">Perbankan Peribadi</a></li>
								<li><a href="#" class="method-ob" data-value="fpx-2">Perbankan Korporat</a></li>
								</ul>
								</div>
							@endif
							@if($ebpg)
								<div class="btn-group">
								<a href="#" class="btn bg-blue-steel method-ob" data-value="ebpg">Kad Kredit</a>
								</div>
							@endif
						@else
							<div class="btn-group">
								<a href="#" class="btn btn-block bg-primary method-ob" data-value="direct">Teruskan</a>
							</div>
						@endif
					</div>
				</div>
			{!! Former::close() !!}
		@else
			<a href="{{ route('dashboard') }}" class="btn btn-primary pull-right">Akaun Saya</a>
		@endif
		<div class="clearfix"></div>
	</div>

	@if($amount > 0.00 && isset($fpx))
		<b>Perbankan Korporat</b>
		<br><br>
		<img src="{{ asset('images/banks/fpx/b2b/abb.png') }}">
		<img src="{{ asset('images/banks/fpx/b2b/allianz.png') }}">
		<img src="{{ asset('images/banks/fpx/b2b/ambank.png') }}">
		<img src="{{ asset('images/banks/fpx/b2b/cimb.png') }}">
		<img src="{{ asset('images/banks/fpx/b2b/cimb2.png') }}">
		<img src="{{ asset('images/banks/fpx/b2b/hlb.png') }}">
		<img src="{{ asset('images/banks/fpx/b2b/kfh.png') }}">
		<img src="{{ asset('images/banks/fpx/b2b/m2e.png') }}">
		<img src="{{ asset('images/banks/fpx/b2b/pbe.png') }}">
		<img src="{{ asset('images/banks/fpx/b2b/rhb.png') }}">
		<img src="{{ asset('images/banks/fpx/b2b/uob.png') }}">
		<br><br><br>
		<b>Perbankan Peribadi</b>
		<br><br>
		<img src="{{ asset('images/banks/fpx/b2c/ambank.png') }}">
		<img src="{{ asset('images/banks/fpx/b2c/bimb.png') }}">
		<img src="{{ asset('images/banks/fpx/b2c/cimb.png') }}">
		<img src="{{ asset('images/banks/fpx/b2c/hlb.png') }}">
		<img src="{{ asset('images/banks/fpx/b2c/m2e.png') }}">
		<img src="{{ asset('images/banks/fpx/b2c/m2u.png') }}">
		<img src="{{ asset('images/banks/fpx/b2c/pbe.png') }}">
		<img src="{{ asset('images/banks/fpx/b2c/rhb.png') }}">
		<img src="{{ asset('images/banks/fpx/b2c/uob.png') }}">
	@endif
@endsection

@section('scripts')
	<script type="text/javascript">
		$('.method-ob').click(function(){
			method = $(this).data('value');
			$('input[name=method]').val(method);
			$(this).parents('form').submit();
		});
	</script>
@endsection
