@extends('layouts.modernLanding')
@if(config('app.env') === 'production')
    @include('errors.404')
@else
	@section('content')
		<div class="row">
			<div class="col-sm-6 col-sm-offset-3">
				<h1 class="text-center">403</h1>
				<br>
				<p class="text-center">You don't Have a permission to Access this page.</p>
			</div>
		</div>
	@endsection
@endif
