@extends('layouts.default')
@section('content')
	<h2 class="tender-title">
		{{ $organizationunit->name }}
		
		@if(Auth::check())
			<div class="btn-group pull-right">
			@if(Auth::user()->hasRole('Admin'))
				<a href="{{ asset('agencies/'.$organizationunit->id.'/edit') }}" class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i> Kemaskini Agensi</a>
			@endif
			@if(Auth::user()->ability(['Admin', 'Agency Admin', 'Agency User'], []))
				<a href="{{ asset('tenders/create')}}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah Tender / Sebut Harga</a>
			@endif
			</div>
		@endif
	</h2>
	
	<ul class="nav nav-tabs">
		<li><a href="{{action('OrganizationUnitsController@show', $organizationunit->id)}}">Tender &amp; Sebut Harga</a></li>
		<li class="active"><a href="{{action('OrganizationUnitsController@prices', $organizationunit->id)}}">Carta Tender</a></li>
		<li><a href="{{action('OrganizationUnitsController@results', $organizationunit->id)}}">Penender Berjaya</a></li>
		<li class="pull-right"><a href="{{action('OrganizationUnitsController@news', $organizationunit->id)}}">Berita</a></li>
	</ul>
	
	<div class="row">
		<div class="col-md-2">
			<ul class="nav nav-pills nav-stacked">
				<li @if(!Request::get('type')) class="active" @endif><a href="{{action('OrganizationUnitsController@prices', $organizationunit->id)}}" role="tab">Semua</a></li>
				<li @if(Request::get('type') == 'tenders') class="active" @endif><a href="{{action('OrganizationUnitsController@prices', [$organizationunit->id, 'type' => 'tenders'])}}">Tender</a></li>
				<li @if(Request::get('type') == 'quotations') class="active" @endif><a href="{{action('OrganizationUnitsController@prices', [$organizationunit->id, 'type' => 'quotations'])}}">Sebut Harga</a></li>
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
									@if(empty($tender->approver_id))<a class="btn btn-primary btn-xs" href="{{ action('tenders.edit', $tender->id) }}">Kemaskini</a>@endif
									<a class="btn btn-success btn-xs" href="{{ route('tenders.show', $tender->id) }}">Papar</a>
									@if($tender->canCancel() && $tender->publish_prices)
										<a class="btn btn-danger btn-xs" href="{{ route('tenders.publishPrices', $tender->id) }}">
											Batal Siar
										</a>
									@else
										<a class="btn btn-warning btn-xs" href="{{ route('tenders.publishPrices', $tender->id) }}">
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
