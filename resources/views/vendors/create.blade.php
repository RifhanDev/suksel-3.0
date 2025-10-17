@extends('layouts.default')
@section('styles')

	<link href="{{ asset('css/form.css') }}" rel="stylesheet">

@endsection
@section('content')
    	<h2>Masukkan Syarikat Baru</h2>
    	<br>
    	{!! Former::open_for_files(action('VendorsController@store'))->addClass('form-uppercase jq-validate') !!}
    		@include('vendors.form')
	    	<div class="well">
	        	<a href="#" id="submit" class="btn btn-primary">Hantar</a>
	        	<button type="button" id="next" class="btn btn-primary">Seterusnya</button>

	        	@if(App\Vendor::canList())
	        		<a href="{{action('VendorsController@index')}}" class="btn btn-default pull-right">Senarai Syarikat</a>
	        	@endif
	    	</div>
    	{!! Former::close() !!}
@endsection