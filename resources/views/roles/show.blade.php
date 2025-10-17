@extends('layouts.default')
@section('content')
	<h2>Paparan Peranan</h2>
	<hr>
	{!! Former::open() !!}
	{!! Former::populate($role) !!}
	@include('roles.form')
	@include('roles.actions-footer')
@endsection
