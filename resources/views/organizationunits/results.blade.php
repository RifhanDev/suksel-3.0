
@extends('layouts.default')
@section('content')
	<h2 class="tender-title">
		{{$organizationunit->name}}

		@if(Auth::check())
			<div class="btn-group pull-right">
				@if(Auth::user()->hasRole('Admin'))
					<a href="{{ route('agencies.edit', $organizationunit->id) }}" class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i> Kemaskini Agensi</a>
				@endif
				@if(Auth::user()->ability(['Admin', 'Agency Admin', 'Agency User'], []))
					<a href="{{ route('tenders.create') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah Tender / Sebut Harga</a>
				@endif
			</div>
		@endif
	</h2>

	<ul class="nav nav-tabs">
		<li><a href="{{ action('OrganizationUnitsController@show', $organizationunit->id) }}">Tender &amp; Sebut Harga</a></li>
		<li><a href="{{ action('OrganizationUnitsController@prices', $organizationunit->id) }}">Carta Tender</a></li>
		<li class="active"><a href="{{ action('OrganizationUnitsController@results', $organizationunit->id) }}">Penender Berjaya</a></li>
		<li class="pull-right"><a href="{{ action('OrganizationUnitsController@news', $organizationunit->id) }}">Berita</a></li>
	</ul>

	<div class="row">
		<div class="col-md-2">
			<ul class="nav nav-pills nav-stacked">
			<li @if(!Request::get('type')) class="active" @endif><a href="{{action('OrganizationUnitsController@results', $organizationunit->id)}}" role="tab">Semua</a></li>
			<li @if(Request::get('type') == 'tenders') class="active" @endif><a href="{{action('OrganizationUnitsController@results', [$organizationunit->id, 'type' => 'tenders'])}}">Tender</a></li>
			<li @if(Request::get('type') == 'quotations') class="active" @endif><a href="{{action('OrganizationUnitsController@results', [$organizationunit->id, 'type' => 'quotations'])}}">Sebut Harga</a></li>
			</ul>
		</div>

		<div class="col-md-10">
			<table class="DT2 table table-hover table-compact">
				<thead class="bg-blue-selangor">
					<tr>
						<th class="col-lg-2">Tarikh Tutup</th>
						<th>No / Tajuk</th>
						@if(Auth::check() && App\Tender::canShowUpdate($organizationunit->id))<th class="col-lg-2">&nbsp;</th>@endif
					</tr>
				</thead>
				<tbody>
					@foreach($tenders as $tender)
						<tr>
							<td>{{\Carbon\Carbon::parse($tender->submission_datetime)->format('j M Y')}}</td>
							<td>
							<strong>@if(Auth::check() && !Auth::user()->hasRole('Vendor') && $tender->invitation)<i class="fa fa-lock"></i>@endif {{$tender->ref_number}}</strong>
							<br>
							<a href="{{action('TendersController@show', $tender->id)}}">{{$tender->name}}</a>
							</td>
							@if(Auth::check() && App\Tender::canShowUpdate($organizationunit->id))
							<td>
								<div class="btn-group btn-group-vertical">
									@if(empty($tender->approver_id))
										<a class="btn btn-primary btn-xs" href="{{ route('tenders.edit', $tender->id) }}">Kemaskini</a>
									@endif
										<a class="btn btn-success btn-xs" href="{{ route('tenders.show', $tender->id) }}">Papar</a>
									@if($tender->canCancel() && $tender->publish_winner)
										<a class="btn btn-danger btn-xs" href="{{ route('tenders.publishWinner', $tender->id) }}">
											Batal Siar
										</a>
									@else
										<a class="btn btn-warning btn-xs" href="{{ route('tenders.publishWinner', $tender->id) }}">
											Umum
										</a>
									@endif
								</div>
							</td>
							@endif
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endsection
@section('scripts')

	<script src="{{ asset('js/datatables.js') }}"></script>
	<script>
		$('.DT2').each(function(){
		  	var target = $(this);
		  	var path = target.data('path');
		  	var DT = target.DataTable({
			    	ajax: path,
			    	stateSave: true,
			    	language: {
				      sEmptyTable: "Tiada data",
				      sInfo: "Paparan dari _START_ hingga _END_ dari _TOTAL_ rekod",
				      sInfoEmpty: "Paparan 0 hingga 0 dari 0 rekod",
				      sInfoFiltered: "(Ditapis dari jumlah _MAX_ rekod)",
				      sInfoPostFix: "",
				      sInfoThousands: ",",
				      sLengthMenu: "Papar _MENU_ rekod",
				      sLoadingRecords: "Diproses...",
				      sProcessing: "Sedang diproses...",
				      sSearch: "Carian:",
				      sZeroRecords: "Tiada padanan rekod yang dijumpai.",
				      oPaginate: {
				      sFirst: "Pertama",
				      sPrevious: "Sebelum",
				      sNext: "Kemudian",
				      sLast: "Akhir"
			      },
			      oAria: {
			        sSortAscending: ": diaktifkan kepada susunan lajur menaik",
			        sSortDescending: ": diaktifkan kepada susunan lajur menurun"
			      }
		    	},
		    	aaSorting: []
		  	});
		});
	</script>

@endsection
