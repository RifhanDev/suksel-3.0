@extends('layouts.default')
@section('content')
	<h2>Edit Subscription</h2>
	<hr>
	{!! Former::open(action('SubscriptionsController@update', [$parent_id, $subscription->id])) !!}
		{!! Former::populate($subscription)!!}
		{!! Former::hidden('_method', 'PUT')!!}
		@include('subscriptions.form')
		@include('subscriptions.actions-footer', ['has_submit' => true])
@endsection