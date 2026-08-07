@extends('layouts.modernLanding')
@section('content')
	<div class="row">
		<div class="col-sm-12 col-sm-offset-3">
			<h1 class="text-center">403</h1>
			<br>
			<p class="text-center">{{ $exception->getMessage() ?: "You don't have permission to access this page." }}</p>
		</div>
	</div>
@endsection
