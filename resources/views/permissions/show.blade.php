@extends('layouts.default')
@section('content')
	<h2>Paparan Kebenaran</h2>
	<hr>
	{!! Former::open() !!}
	{!! Former::populate($permission) !!}
	@include('permissions.form')
	@include('permissions.actions-footer')
@endsection
@section('scripts')

	<script src="{{ asset('js/show.js') }}"></script>

@endsection
