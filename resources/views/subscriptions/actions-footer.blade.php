<div class="well">
	@if(isset($has_submit))
		<button class="btn btn-primary">Make Payment</button>
	@endif
	@if(stristr(Route::currentRouteAction(), 'SubscriptionsController@index') === 'SubscriptionsController@index')
		@if($user->ability(['Admin'], ['Vendors:Show']))
			<a href="{{action('VendorsController@show', $parent->id)}}" class="btn btn-default">Back To Vendor Profile</a>
		@else
			<a href="{{action('SubscriptionsController@create', $parent_id)}}" class="btn btn-default">Renew Subscription</a>
		@endif
	@endif
</div>