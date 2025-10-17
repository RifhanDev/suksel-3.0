@extends('layouts.default')
@section('styles')

	<link href="{{ asset('css/form.css') }}" rel="stylesheet">

@endsection
@section('content')

	<h2 class="tender-title">Masukkan Banner Baru</h2>
	
	{!! Former::open_for_files(url('banners')) !!}
		@include('banners.form')
		
		<div class="well">
			{!! Former::submit('Hantar')->class('btn btn-primary') !!}
			<a href="{{ asset('banners') }}" class="btn btn-default pull-right">Senarai Banner</a>
		</div>
	{!! Former::close() !!}
	
@endsection