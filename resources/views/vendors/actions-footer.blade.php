<div class="d-flex justify-content-end align-items-center flex-wrap gap-2 p-3 bg-white border rounded-3 shadow-sm">
	
    {{-- Save Button --}}
    @if (isset($has_submit))
		<button class="btn btn-primary d-flex align-items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
            Simpan
        </button>
	@endif

    {{-- Index Page Actions --}}
	@if (stristr(Route::currentRouteAction(), 'VendorsController@index') === 'VendorsController@index')
		@if (App\Vendor::canCreate())
			<a href="{{ action('VendorsController@create') }}" class="btn btn-primary d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Masukkan Syarikat Baru
            </a>
		@endif
		@if (App\Vendor::canList())
			<a href="{{ action('VendorsController@emails') }}" class="btn btn-light border text-secondary d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                Senarai Pengesahan Emel
            </a>
		@endif
	@else
        {{-- Back to List --}}
		@if (App\Vendor::canList())
			<a href="{{ action('VendorsController@index') }}" class="btn btn-light border text-secondary d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Senarai Syarikat
            </a>
		@endif
	@endif

    {{-- Show Page Actions --}}
	@if (isset($vendor) && stristr(Route::currentRouteAction(), 'VendorsController@show') === 'VendorsController@show')
		
        {{-- Approval --}}
        @if (!$vendor->approval_1_id && $vendor->completed)
			@if (!$vendor->approval_1_id && $user->ability(['Admin', 'Registration Assessor'], ['Vendor:approve']))
				<a href="{{ action('VendorsController@approve', [$vendor->id]) }}" class="btn btn-primary d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    Lulus
                </a>
				<button type="button" id="reject" class="btn btn-danger d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    Tolak
                </button>
			@endif
		@endif

        {{-- Update --}}
		@if ($vendor->canUpdate())
			<a href="{{ action('VendorsController@edit', $vendor->id) }}" class="btn btn-light border text-secondary d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Kemaskini
            </a>
		@endif

        {{-- Delete --}}
		@if ($vendor->canDelete())
			{!! Former::open(action('VendorsController@destroy', $vendor->id))->class('d-inline-block') !!}
			{!! Former::hidden('_method', 'DELETE') !!}
			<button type="button" class="btn btn-danger confirm-delete d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                Padam
            </button>
			{!! Former::close() !!}
		@endif

        {{-- Subscriptions --}}
		@if ($user->ability(['Admin', 'Registration Assessor'], ['Vendor:show']))
			<a href="{{ action('SubscriptionsController@index', $vendor->id) }}" class="btn btn-light border text-secondary">Langganan</a>
		@endif

        {{-- Requests --}}
		@if ($user->ability(['Admin', 'Registration Assessor'], ['Vendor:approve']))
			<a href="{{ action('ChangeRequestsController@index', $vendor->id) }}" class="btn btn-light border text-secondary">Permintaan Kemaskini</a>
		@endif

        {{-- Blacklist --}}
		@if ($user->ability(['Admin', 'Registration Assessor'], ['Vendor:blacklist']))
			<a href="{{ action('VendorsController@blacklist', $vendor->id) }}" class="btn btn-dark d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg>
                Senarai Hitam
            </a>
		@endif
	@endif
</div>