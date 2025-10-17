@extends('layouts.default')
@section('content')
	<h2>New Comment</h2>
	<hr>
	{!! Former::open(route('comments.store')) !!}
	@include('comments.form')
	@include('comments.actions-footer', ['has_submit' => true])
@endsection