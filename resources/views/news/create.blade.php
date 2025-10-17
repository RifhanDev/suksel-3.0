@extends('layouts.default')
@section('styles')

	<link href="{{ asset('css/form.css') }}" rel="stylesheet">

@endsection
@section('content')
	<h1 class="tender-title">Berita Baru</h1>
	
	{!! Former::open( url('news')) !!}
	@include('news.form')
	
	<div class="well">
	<input type="submit" value="Hantar" class="btn btn-primary confirm">
	
	<a href="{{ asset('news') }}" class="btn btn-default pull-right">Arkib Berita</a>  
	</div>
	{!! Former::close() !!}
@endsection