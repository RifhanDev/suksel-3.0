@extends('layouts.modern')
@section('content')
	<h2>Lihat Templat Penolakan</h2>
	<hr>
	{!! Former::open() !!}
	{!! Former::populate($template) !!}
	@include('reject-template.form')
	@include('reject-template.actions-footer')
@endsection
