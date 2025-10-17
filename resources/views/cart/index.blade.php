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
					<th>Padam</th>
				</tr>
			</thead>
			<tbody>
				@forelse($tenders as $tender)
					<tr>
						<td><a href="{{ asset('agencies/'.$tender->organization_unit_id) }}">{{ $tender->tenderer->name }}</a></td>
						<td>
							<a href="{{ asset('tenders/'.$tender->id) }}">
								<strong>{{ $tender->ref_number }}</strong>
								<br>{{ $tender->name }}
							</a>
						</td>
						<td>{{\Carbon\Carbon::parse($tender->document_start_date)->format('j M Y')}}</td>
						<td>{{\Carbon\Carbon::parse($tender->submission_datetime)->format('j M Y')}}</td>
						<td>RM {{ sprintf('%.2f', $tender->price) }}</td>
						<td><a href="{{ asset('cart/delete/'.$tender->id) }}" class="btn btn-danger btn-xs">Padam</a></td>
					</tr>
				@empty
					<tr>
						<td colspan="6"><center>Tiada tender dalam senarai tempahan</center></td>
					</tr>
				@endforelse
			</tbody>
			@if(count($tenders) > 0)
				<tfoot>
					<tr>
						<td colspan="4" class="align-right">Jumlah Tender</td>
						<td colspan="2">{{ count($tenders) }}</td>
					</tr>
					<tr>
						<td colspan="4" class="align-right"><strong>Jumlah Bayaran</strong></td>
						<td colspan="2">RM {{ sprintf('%.2f', $amount) }}</td>
					</tr>
				</tfoot>
			@endif
		</table>
		<br>
		@if(count($tenders) > 0)
			<div class="well">
				<a href ="{{ asset('cart/clear') }}" class="btn btn-danger">Batal Semua Tempahan</a>
				<a href ="{{ asset('cart/checkout') }}" class="btn btn-primary pull-right">
					Teruskan Pembayaran <span class="glyphicon glyphicon-chevron-right"></span>
				</a>
				<div class ="clearfix"></div>
			</div>
		@endif
	@endif

@endsection
