@extends('layouts.default')
@section('content')

	<h2>View Subscription</h2>
	<hr>
	{!! Former::open() !!}
	{!! Former::populate($subscription) !!}
	@include('subscriptions.form')
	@include('subscriptions.actions-footer')

@endsection
@section('scripts')

	<script src="{{ asset('js/show.js') }}"></script>

@endsection