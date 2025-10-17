@extends('layouts.default')
@section('content')
	<h2>Edit Comment</h2>
	<hr>
	{!! Former::open(route('comments.update', $comment->id)) !!}
	{!! Former::populate($comment) !!}
	{!! Former::hidden('_method', 'PUT') !!}
	@include('comments.form')
	@include('comments.actions-footer', ['has_submit' => true])
@endsection