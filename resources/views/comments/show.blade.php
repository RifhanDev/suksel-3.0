@extends('layouts.default')
@section('content')
	<h2>View Comment</h2>
	<hr>
	{!! Former::open() !!}
	{!! Former::populate($comment) !!}
	@include('comments.form')
	@include('comments.actions-footer')
@endsection
@section('scripts')

	<script src="{{ asset('js/show.js') }}"></script>

@endsection