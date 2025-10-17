@extends('layouts.default')
@section('styles')

	<link href="{{ asset('css/form.css') }}" rel="stylesheet">

@endsection
@section('content')

	<h2 class="tender-title">Masukkan Pekeliling Baru</h2>
	
	{!! Former::open_for_files(url('circulars')) !!}
		@include('circulars.form')
		
		<div class="well">
			{!! Former::submit('Hantar')->class('btn btn-primary') !!}
			<a href="{{ asset('circulars') }}" class="btn btn-default pull-right">Senarai Pekeliling</a>
		</div>
	{!! Former::close() !!}
	
@endsection