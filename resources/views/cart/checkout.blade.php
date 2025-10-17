@extends('layouts.default')

@section('content')
	<h4>Senarai Tempahan</h4>
	@if(!$fpx && !$ebpg)
		<div class="alert alert-danger">Harap Maaf! Pembayaran tidak dapat dilakukan buat masa ini.</div>
	@else
		<table class="DT table table-hover table-compact">
			<thead class="bg-blue-selangor">
				<tr>
					<th class="col-lg-2">Petender</th>
					<th class="col-lg-4">No / Tajuk</th>
					<th>Tarikh Jual</th>
					<th>Tarikh Tutup</th>
					<th>Harga Dokumen</th>
				</tr>
			</thead>
			<tbody>
				@forelse($tenders as $tender)
					<tr>
						<td><a href="{{ asset('agencies/'.$tender->organization_unit_id) }}">{{ $tender->tenderer->name }}</a></td>
						<td>
							<a href="{{ asset('tenders/'.$tender->id) }}">
								<strong>{{$tender->ref_number}}</strong>
								<br>{{$tender->name}}
							</a>
						</td>
						<td>{{\Carbon\Carbon::parse($tender->document_start_date)->format('j M Y')}}</td>
						<td>{{\Carbon\Carbon::parse($tender->submission_datetime)->format('j M Y')}}</td>
						<td>RM {{ sprintf('%.2f', $tender->price) }}</td>
					</tr>
				@empty
					<tr>
						<td colspan="5"><center>Tiada tender dalam senarai tempahan</center></td>
					</tr>
				@endforelse
			</tbody>
			@if(count($tenders) > 0)
				<tfoot>
					<tr>
						<td colspan="4" class="align-right">Jumlah Tender</td>
						<td>{{ count($tenders) }}</td>
					</tr>
					<tr>
						<td colspan="4" class="align-right"><strong>Jumlah Bayaran</strong></td>
						<td>RM {{ sprintf('%.2f', $amount) }}</td>
					</tr>
				</tfoot>
			@endif
		</table>
		<br>
		@if(count($tenders) > 0)
			{!! Former::open( route('cart.process'))->class('form-inline disabled-submit') !!}
				<input type="hidden" name="method">
				
				<div class="well">
				
					<p id="payment-kinds" class="pull-left">
						<span>Pembelian Dokumen Tender / Sebut Harga boleh dilakukan menggunakan</span><br><br>
						@if($ebpg)
							<i class="icon icon-visa"></i>
							<i class="icon icon-mastercard"></i>
						@endif
						@if($fpx)<i class="temp-icon temp-icon-fpx"></i>@endif
					</p>
				
					<div id="payment-options" class="pull-right">
						<div class="text">
							@if($amount > 0.00)
								Pilihan Pembayaran
							@else
								Tiada Pembayaran Perlu Dilakukan
							@endif
						</div>
						<div class="btn-toolbar">
							@if($amount > 0.00)
								@if($fpx)
									<div class="btn-group">
										<a href="#" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Internet Banking (FPX) <span class="caret"></span></a>
										<ul class="dropdown-menu">
										<li><a href="#" class="method-ob" data-value="fpx-1">Perbankan Peribadi</a></li>
										@unless($fpx->private_key == 'b2c')
											<li><a href="#" class="method-ob" data-value="fpx-2">Perbankan Korporat</a></li>
										@endunless
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
				
					<div class="clearfix"></div>
				</div>
			{!! Former::close() !!}

			<!-- @if($fpx)
			<b>Perbankan Korporat</b>
			<br><br>
			<img src="/assets/images/banks/fpx/b2b/abb.png">
			<img src="/assets/images/banks/fpx/b2b/allianz.png">
			<img src="/assets/images/banks/fpx/b2b/ambank.png">
			<img src="/assets/images/banks/fpx/b2b/cimb.png">
			<img src="/assets/images/banks/fpx/b2b/cimb2.png">
			<img src="/assets/images/banks/fpx/b2b/hlb.png">
			<img src="/assets/images/banks/fpx/b2b/kfh.png">
			<img src="/assets/images/banks/fpx/b2b/m2e.png">
			<img src="/assets/images/banks/fpx/b2b/pbe.png">
			<img src="/assets/images/banks/fpx/b2b/rhb.png">
			<img src="/assets/images/banks/fpx/b2b/uob.png">
			<br><br><br>
			<b>Perbankan Peribadi</b>
			<br><br>
			<img src="/assets/images/banks/fpx/b2c/ambank.png">
			<img src="/assets/images/banks/fpx/b2c/bimb.png">
			<img src="/assets/images/banks/fpx/b2c/cimb.png">
			<img src="/assets/images/banks/fpx/b2c/hlb.png">
			<img src="/assets/images/banks/fpx/b2c/m2e.png">
			<img src="/assets/images/banks/fpx/b2c/m2u.png">
			<img src="/assets/images/banks/fpx/b2c/pbe.png">
			<img src="/assets/images/banks/fpx/b2c/rhb.png">
			<img src="/assets/images/banks/fpx/b2c/uob.png">
			@endif -->

		@endif
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
