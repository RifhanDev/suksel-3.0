@extends('layouts.default')
@section('content')

	<h2>Senarai Hitam Syarikat <span class="label label-default">{{$vendor->name}}</span></h2>
	<br>
	
	{!! Former::open_for_files(action('VendorsController@doBlacklist', $vendor->id)) !!}
		{!! Former::populate($vendor)}}
		{!! Former::hidden('_method', 'PUT') !!}
		
		{!! Former::text('blacklisted_until')
			->label('Sehingga')
			->required()
			->forceValue(date('d/m/Y')) !!}
		
		{!! Former::textarea('blacklist_reason')
			->label('Alasan')
			->rows(2)
			->required() !!}
		
		<div class="well">
			<input type="submit" class="btn btn-primary confirm" value="Senarai Hitam">
			@if(App\Vendor::canList())
				<a href="{{action('VendorsController@index')}}" class="btn btn-default pull-right">Senarai Syarikat</a>
			@endif
		</div>
	{!! Former::close() !!}

@endsection
@section('scripts')

	<script>
		$('#blacklisted_until')
			.datepicker({
				format: 'dd/mm/yyyy',
				minDate: (new Date())
			});
	</script>

@endsection