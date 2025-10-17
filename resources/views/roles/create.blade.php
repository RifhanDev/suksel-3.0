@extends('layouts.default')
@section('content')
	<h2>Tambah Peranan Baru</h2>
	<hr>
	{!! Former::open(url('roles')) !!}
		@include('roles.form')
		<div class="well">
			{!! Former::submit('Hantar')->addClass('btn btn-primary confirm') !!}
			
			@if(App\Role::canList())
			<a href="{{ asset('roles')}}" class="btn btn-default">Senarai</a>
			@endif
		</div>
	{!! Former::close() !!}
@endsection
