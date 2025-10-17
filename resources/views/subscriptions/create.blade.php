@extends('layouts.default')
@section('content')
	<h2>Renew Subscription</h2>
	<hr>
	{!! Former::open(action('SubscriptionsController@store', $parent_id)) !!}
	<h3>New Expiry Date: {{$parent->getNewExpiryDates()[1]!!}</h3>
	<h3>Amount Charged: RM 100</h3>
	<br>
	<br>
    @include('subscriptions.actions-footer', ['has_submit' => true])
@endsection