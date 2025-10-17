@extends('layouts.default')
@section('styles')

	<link href="{{ asset('css/form.css') }}" rel="stylesheet">

@endsection
@section('content')

	<h2>Kemaskini Syarikat <span class="label label-default">{{ $vendor->name }}</span></h2>
	<br>
	{!! Former::open_for_files( url('vendors/'.$vendor->id))->addClass('form-uppercase jq-validate') !!}
		{!! Former::populate($vendor) !!}
		{!! Former::hidden('_method', 'PUT') !!}
		@include('vendors.form')
		
		<div class="well">
			<a href="#" id="submit" class="btn btn-primary">Hantar</a>
			<button type="button" id="next" class="btn btn-primary">Seterusnya</button>
			
			@if(!Auth::user()->hasRole('Vendor') && App\Vendor::canList())
				<a href="{{ asset('vendors') }}" class="btn btn-default pull-right">Senarai Syarikat</a>
			@endif
			@if(Auth::user()->hasRole('Vendor'))
				<a href="{{ asset('vendor') }}" class="btn btn-default pull-right">Maklumat Syarikat</a>
			@endif
		</div>
	{!! Former::close() !!}

@endsection