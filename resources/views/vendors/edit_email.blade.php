@extends('layouts.default')
@section('content')
	<h2 class="tender-title">Kemaskini Emel / No. Pendaftaran</h2>
	{!! Former::open(action('VendorsController@updateEmail', $vendor->id)) !!}
		{!! Former::hidden('_method', 'PUT') !!}
		{!! Former::populate($vendor) !!}
		
		{!! Former::text('vendor')
			->forceValue($vendor->name)
			->label('Nama Syarikat')
			->disabled() !!}
		
		{!! Former::text('name')->disabled()->label('Nama')->forceValue($vendor->user->name) !!}
		{!! Former::text('email')->label('Alamat Emel')->forceValue(Request::old('email', $vendor->user->email)) !!}
		{!! Former::text('registration')->label('No. Pendaftaran')->forceValue(Request::old('registration', $vendor->registration)) !!}
		
		<div class="well">
			<input type="submit" value="Kemaskini" class="btn btn-primary">
			
			@if($vendor->canShow())
				<a href="{{ asset('vendors/'.$vendor->id) }}" class="btn btn-default pull-right">Maklumat Syarikat</a>
			@endif
		</div> 
	{!! Former::close() !!}
@endsection
