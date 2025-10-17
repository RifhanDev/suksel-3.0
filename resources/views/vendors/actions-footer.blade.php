<div class="well">
	@if(isset($has_submit))
		<button class="btn btn-primary">Simpan</button>
	@endif
	@if(stristr(Route::currentRouteAction(), 'VendorsController@index') === 'VendorsController@index')
		@if(App\Vendor::canCreate())
			<a href="{{ action('VendorsController@create') }}" class="btn btn-default">Masukkan Syarikat Baru</a>
		@endif
		@if(App\Vendor::canList())
			<a href="{{ action('VendorsController@emails') }}" class="btn btn-default">Senarai Pengesahan Emel</a>
		@endif
	@else
		@if(App\Vendor::canList())
			<a href="{{ action('VendorsController@index') }}" class="btn btn-default">Senarai Syarikat</a>
		@endif
	@endif
	@if(isset($vendor) && stristr(Route::currentRouteAction(), 'VendorsController@show') === 'VendorsController@show')
		@if(!$vendor->approval_1_id && $vendor->completed)
			@if(!$vendor->approval_1_id && $user->ability(['Admin', 'Registration Assessor'], ['Vendor:approve']))
				<a href="{{action('VendorsController@approve', [$vendor->id])}}" class="btn btn-primary">Lulus</a>
				<button type="button" id="reject" class="btn btn-danger">Tolak</button>
			@endif
		@endif
		@if($vendor->canUpdate())
			<a href="{{action('VendorsController@edit', $vendor->id)}}" class="btn btn-default">Kemaskini</a>
		@endif
		@if($vendor->canDelete())
			{!! Former::open(action('VendorsController@destroy', $vendor->id))->class('form-inline') !!}
			{!! Former::hidden('_method', 'DELETE') !!}
			<button type="button" class="btn btn-danger confirm-delete">Padam</button>
			{!! Former::close() !!}
		@endif
		@if($user->ability(['Admin', 'Registration Assessor'], ['Vendor:show']))
			<a href="{{action('SubscriptionsController@index', $vendor->id)}}" class="btn btn-default">Langganan</a>
		@endif
		@if($user->ability(['Admin', 'Registration Assessor'], ['Vendor:approve']))
			<a href="{{action('ChangeRequestsController@index', $vendor->id)}}" class="btn btn-default">Permintaan Kemaskini</a>
		@endif
		@if($user->ability(['Admin', 'Registration Assessor'], ['Vendor:blacklist']))
			<a href="{{action('VendorsController@blacklist', $vendor->id)}}" class="btn btn-default">Senarai Hitam</a>
		@endif
	@endif
</div>