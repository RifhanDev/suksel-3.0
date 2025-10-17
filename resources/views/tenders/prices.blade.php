@extends('layouts.default')
@section('styles')

	<link href="{{ asset('css/form.css') }}" rel="stylesheet">

@endsection
@section('content')

	<div class="btn-group pull-left btn-actions">
		<a href="{{ asset('agencies/'.$tender->tenderer->id) }}" class="btn btn-warning btn-sm">
			<i class="fa fa-group"></i> Senarai Tender oleh {{ $tender->tenderer->name }}
		</a>
	</div>

	@if(Auth::check())
		<div class="btn-group pull-right btn-actions">
			@if(Auth::user()->hasRole('Admin'))
				<a href="{{ asset('tenders')}}" class="btn btn-sm">
					<i class="fa fa-chevron-up"></i> Senarai Tender
				</a>
			@endif
			@if($tender->canUpdate())
				<a href="{{ asset('tenders/'.$tender->id.'/edit') }}" class="btn btn-primary btn-sm">
					<i class="fa fa-pencil-square-o"></i> Kemaskini
				</a>
			@endif
		</div>
	@endif

	<div class="clearfix"></div>

	<div class="tender-ref-number">{{ $tender->ref_number }}</div>
	<h2 class="tender-title">{{ $tender->name }}</h2>

	@if($tender->canShowTabs())
		<ul class="nav nav-tabs nav-justified">
			<li><a href="{{ asset('tenders/'.$tender->id) }}">Maklumat Tender / Sebut Harga</a></li>
			<li class="active"><a href="{{ asset('tenders/'.$tender->id.'/vendors') }}">Maklumat Syarikat</a></li>
            @if (Auth::check() &&
                    $tender->canException() &&
                    auth()->user()->ability(['Admin', 'Agency Admin', 'Agency User'], ['ExceptionTender:list']))
                <li><a href="{{ asset('tenders/' . $tender->id . '/exceptions') }}">Maklumat Kebenaran Khas <span
                            class="badge">{{ $tender->exceptions()->where('status', 0)->count() }}</span></a></li>
            @endif
		</ul>
	@endif

	@if($tender->publish_winner)
		<ul class="nav nav-pills">
    		<li{{ !Request::get('show') ? ' class="active"' : '' }}><a href="{{ asset('tenders/'.$tender->id.'/vendors') }}">Carta Tender</a></li>
    		<li{{ Request::get('show') == 'winner' ? ' class="active"' : '' }}><a href="{{ route('tenders.vendors', [$tender->id, 'show' => 'winner']) }}">Penender Berjaya</a></li>
		</ul>
	@endif

	@if(Request::get('show') == 'winner')
		@if(isset($winner) && $tender->publish_winner)
			<table class="table table-bordered">
				<tr>
					<th class="col-xs-2">Nama Syarikat</th>
					<td>{{ $winner->vendor->name }}</td>
				</tr>
				<tr>
					<th>Tempoh Siap</th>
					<td>{!! $winner->project_timeline ?: '<span class="glyphicon glyphicon-remove"></span>' !!}</td>
				</tr>
				<tr>
					<th>Harga Tawaran</th>
					<td>{{ $winner->price ? 'RM '. number_format($winner->price, 2) : '<span class="glyphicon glyphicon-remove"></span>' }}</td>
				</tr>
			</table>
		@else
			<div class="alert alert-info">Penender Berjaya belum diumumkan.</div>
		@endif
	@else

	@if(count($prices) > 0)
		<table class="table table-bordered">
			<thead class="bg-blue-selangor">
				<tr>
					<th>Label Syarikat</th>
					<th class="col-lg-2">Harga</th>
				</tr>
			</thead>
			<tbody>
				@foreach($prices as $purchase)
					<tr>
						<td>{{ $purchase->label }}</td>
						<td>RM {{ number_format($purchase->price, 2) }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	@else
		<div class="alert alert-info">Tiada Syarikat sertai.</div>
	@endif

	@endif

@endsection
@section('scripts')

	<script src="{{ asset('js/tender-vue.js') }}"></script>

@endsection
